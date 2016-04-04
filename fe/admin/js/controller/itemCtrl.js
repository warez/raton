angular.module("JRatonApp").controller("ItemController", ['$scope', '$location', 'LoaderService', 'WPPathService',
    'CategoryUtils', 'ItemService', '$uibModal', '$sessionStorage', 'ModalService', 'itemViewOpt',

    function ($scope, $location, LoaderService, WPPathService, CategoryUtils, ItemService, $uibModal,
              $sessionStorage, ModalService, itemViewOpt) {

        var ctrl = this;
        ctrl.mainCtrl = $scope.$parent.mainCtrl;

        ctrl.itemViewOpt = itemViewOpt;
        ctrl.selectedCat = itemViewOpt.fromCategory ? $sessionStorage["category"] : undefined;

        ctrl.itemsData = {};

        ctrl.filter = {
            title: '',
            description: '',
            request_approve_type: 'a',
            approved_type: 'a',
            creationTimeCond: "null",
            creationTime: null,
            updateTimeCond: "null",
            updateTime: null,
            page: 1,
            per_page: 10
        };

        ctrl.goToReview = function(item) {
            $sessionStorage["item"] = item;
            $location.path('/item/' + item.id + "/reviews");
        };

        var categoriesFromSession = $sessionStorage["categories"];

        ctrl.onCategoryChange = function (data) {
            ctrl.selectedCat = data.categoryObj;
            ctrl.filter.from = ctrl.selectedCat.id;
        };

        ctrl.load = function() {

            if (!categoriesFromSession)
                ctrl.mainCtrl.loadCategories().then(function(data) {
                    ctrl.categories = data;
                });

            if(itemViewOpt.fromCategory) {
                ctrl.filter.from = ctrl.selectedCat.id;
                ctrl.search();
            }
        };

        ctrl.clear = function() {

            ctrl.itemsData.items = [];

            ctrl.filter.title = "";
            ctrl.filter.description = "";
            ctrl.filter.request_approve_type = "a";
            ctrl.filter.approved_type = "a";
            ctrl.filter.creationTimeCond = "null";
            ctrl.filter.creationTime = null;
            ctrl.filter.updateTimeCond = "null";
            ctrl.filter.updateTime = null;
            ctrl.filter.page = 1;
        };

        ctrl.search = function(page) {
            LoaderService.start();

            if(page)
                ctrl.filter.page = page;

            ItemService.search(ctrl.filter).$promise.then(function(data) {


                LoaderService.stop();

                ctrl.itemsData = angular.copy(data);
                ctrl.filter.page = data.page;
                ctrl.per_page = data.per_page;

                ctrl.searchedCat = angular.copy(ctrl.selectedCat);

            }, function(error) {
                LoaderService.stop();
                //TODO
            });
        };

        ctrl.isValidSearch = function() {

            var isTextFilled = ctrl.filter.title.trim().length > 0 ||
                ctrl.filter.description.trim().length > 0 ||
                (ctrl.filter.categoryId && ctrl.filter.categoryId != "null");

            if(isTextFilled)
                return true;

            return ctrl.filter.request_approve_type != 'a' ||
                ctrl.filter.approved_type != 'a' ||
                (ctrl.filter.creationTimeCond && ctrl.filter.creationTimeCond != "null" && ctrl.filter.creationTime != null) ||
                (ctrl.filter.updateTimeCond && ctrl.filter.updateTimeCond != "null" && ctrl.filter.updateTime != null);
        };

        ctrl.createItem = function() {

            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: WPPathService.getPartialUrl() + "/createItemTmpl.html",
                controller: "CreateItemCtrl",
                size: 'sm',
                resolve: {
                    category: function() {
                        return ctrl.selectedCat;
                    }
                }
            });

            modalInstance.result.then(function (item) {

                LoaderService.start();

                ItemService.create(item).$promise.then(function (ret) {
                    LoaderService.stop();
                    ctrl.search(1);
                    //TODO

                }, function (error) {

                    //TODO
                    LoaderService.stop();

                });

            });

        };

        ctrl.onEdit = function(item) {

            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: WPPathService.getPartialUrl() + "/createItemTmpl.html",
                controller: "EditItemCtrl",
                size: 'sm',
                resolve: {
                    item: function () {
                        return ItemService.prepareDBItem(item);
                    }
                }
            });

            modalInstance.result.then(function (newItem) {

                LoaderService.start();

                ItemService.update(newItem).$promise.then(function (ret) {
                    angular.copy(ret, item);
                    LoaderService.stop();
                    //TODO

                }, function (error) {

                    //TODO
                    LoaderService.stop();

                });

            });

        };

        ctrl.onDelete = function(item) {

            var doDelete = function() {
                LoaderService.start();
                ItemService.delete(item).$promise.then(function(data){

                    LoaderService.stop();
                    ctrl.search(1);
                    //TODO

                }, function(error) {
                    LoaderService.stop();
                    //TODO
                })
            };

            var modalOptions = {
                closeButtonText: 'Annulla',
                actionButtonText: "Cancella!",
                headerText: "Attenzione",
                bodyText: "Sei sicuro di voler cancellare l'articolo?"
            };

            ModalService.showModal({}, modalOptions).then(function () {
                doDelete();
            });

        };

        ctrl.pageChanged = function() {
            ctrl.search();
        };

        ctrl.load();
    }

]).controller('EditItemCtrl', function ($scope, $uibModalInstance, ItemService, item) {

    $scope.mode = "EDIT";
    $scope.data = item;
    $scope.title = "Modifica articolo";
    $scope.requestApproveDisabled = false;

    $scope.ok = function () {

        if (!ItemService.testEditItem($scope.data)) {
            //TODO
            return;
        }

        var itemDB = ItemService.prepareDBItem($scope.data);
        $uibModalInstance.close(itemDB);
    };

    $scope.cancel = function () {
        $uibModalInstance.dismiss('cancel');
    };

}).controller('CreateItemCtrl', function ($scope, $uibModalInstance, ItemService, category) {

    $scope.data = {
        id_category: category.id,
        request_approve: 'y',
        approved: 'n'
    };

    $scope.mode = "CREATE";
    $scope.requestApproveDisabled = true;
    $scope.title = "Crea articolo";

    $scope.ok = function () {

        if (!ItemService.testCreateItem($scope.data)) {
            //TODO
            return;
        }

        var itemDB = ItemService.prepareDBItem($scope.data);
        $uibModalInstance.close(itemDB);
    };

    $scope.cancel = function () {
        $uibModalInstance.dismiss('cancel');
    };

});
