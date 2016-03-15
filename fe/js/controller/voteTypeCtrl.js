angular.module("JRatonApp").controller("VoteTypeController", ['$scope', 'LoaderService', 'WPPathService',
    'CategoryService', 'CategoryUtils', 'VoteTypeService', '$uibModal', '$sessionStorage', 'ModalService', 'itemViewOpt',

    function ($scope, LoaderService, WPPathService, CategoryService, CategoryUtils, VoteTypeService,
              $uibModal, $sessionStorage, ModalService, itemViewOpt) {

        var ctrl = this;
        ctrl.mainCtrl = $scope.$parent.mainCtrl;

        ctrl.itemViewOpt = itemViewOpt;
        ctrl.selectedCat = itemViewOpt.fromCategory ? $sessionStorage["category"] : undefined;

        ctrl.itemsData = {};
        ctrl.categories = [];

        var cleanCategories = function(data) {
            var ret = [];
            for(var i = 0; i< data.length; i++) {
                if(data[i].id == "ROOT")
                    continue;
                ret.push( {
                    value: data[i].id,
                    label: data[i].title
                });
            }
            return ret;
        };

        var loadCategories = function() {

            var readTree = function(data) {
                LoaderService.stop();
                var categories = CategoryUtils.levelTree(data);
                ctrl.categories = cleanCategories(categories);
            };

            LoaderService.start();
            CategoryService.getCategoryTree({from: -1}).$promise.then(readTree, function(error) {
                LoaderService.stop();
                //TODO
            })
        };

        var categoriesFromSession = $sessionStorage["categories"];
        if(!categoriesFromSession)
            loadCategories();


        ctrl.filter = {
            category: null
        };

        if(itemViewOpt.fromCategory) {
            ctrl.filter.category = ctrl.selectedCat.id;
        }

        ctrl.clear = function() {
            ctrl.itemsData.items = [];
        };

        var removeFromItems = function(item) {
            var index = ctrl.itemsData.indexOf(item);
            if(index == -1)
                return;

            ctrl.itemsData.splice(index,1);
        };

        ctrl.search = function() {
            LoaderService.start();

            VoteTypeService.search(ctrl.filter).$promise.then(function(data) {

                LoaderService.stop();
                ctrl.itemsData = angular.copy(data);

            }, function(error) {
                LoaderService.stop();
                //TODO
            });
        };

        ctrl.createItem = function() {

            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: WPPathService.getPartialUrl() + "/createVoteTypeTmpl.html",
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
                templateUrl: WPPathService.getPartialUrl() + "/createVoteTypeTmpl.html",
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
                    removeFromItems(item);
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
                bodyText: "Sei sicuro di voler cancellare il tipo di voto?"
            };

            ModalService.showModal({}, modalOptions).then(function () {
                doDelete();
            });

        };

        /*if(itemViewOpt.fromCategory)
            ctrl.search();*/
    }

])

    .controller('EditItemCtrl', function ($scope, $uibModalInstance, ItemService, item) {

    $scope.mode = "EDIT";
    $scope.data = item;
    $scope.title = "Modifica tipo voto";
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

})

    .controller('CreateItemCtrl', function ($scope, $uibModalInstance, ItemService, category) {

    $scope.data = {
        id_category: category.id,
        request_approve: 'y',
        approved: 'n'
    };

    $scope.mode = "CREATE";
    $scope.requestApproveDisabled = true;
    $scope.title = "Crea tipo voto";

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
