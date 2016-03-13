angular.module("JRatonApp").controller("ItemController", ['$scope', '$location', 'LoaderService', 'WPPathService',
    'CategoryUtils', 'ItemService', '$uibModal', '$sessionStorage', 'ModalService', 'itemViewOpt',

    function ($scope, $location, LoaderService, WPPathService, CategoryUtils, ItemService, $uibModal,
              $sessionStorage, ModalService, itemViewOpt) {

        var ctrl = this;

        ctrl.state_approve_items = [
            {label: "Tutti", value: 'a'},
            {label: "In attesa", value: 'y'},
            {label: "Già approvati", value: 'n'},
        ];

        ctrl.enable_state_items = [
            {label: "Tutti", value: 'a'},
            {label: "Approvati", value: 'y'},
            {label: "Non approvati", value: 'n'},
        ];

        ctrl.itemViewOpt = itemViewOpt;
        ctrl.selectedCat = itemViewOpt.fromCategory ? $sessionStorage["category"] : undefined;

        ctrl.itemsData = {};

        ctrl.dateOptions = {
            formatYear: 'yyyy',
            maxDate: new Date(2020, 12, 12),
            minDate: new Date(2016, 1, 1),
            startingDay: 1
        };

        ctrl.filter = {
            title: '',
            description: '',
            request_approve_type: 'a',
            approved_type: 'a',
            page: 1,
            per_page: 10
        };

        if(itemViewOpt.fromCategory) {
            ctrl.filter.from = ctrl.selectedCat.id;
        }

        ctrl.clear = function() {

            ctrl.itemsData.items = [];

            ctrl.filter.title = "";
            ctrl.filter.description = "";
            ctrl.filter.request_approve_type = "a";
            ctrl.filter.approved_type = "a";
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

            }, function(error) {
                LoaderService.stop();
                //TODO
            });
        };

        ctrl.isValidSearch = function() {

            var isTextFilled = ctrl.filter.title.trim().length > 0 ||
                ctrl.filter.description.trim().length > 0;

            if(isTextFilled)
                return true;

            return ctrl.filter.request_approve_type != 'a' ||
                ctrl.filter.approved_type != 'a';
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

        if(itemViewOpt.fromCategory)
            ctrl.search();

        ctrl.pageChanged = function() {
            ctrl.search();
        }
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

});;
