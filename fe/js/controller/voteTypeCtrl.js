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

        ctrl.isValidSearch = function () {
            return ctrl.filter.categoryId && ctrl.filter.categoryId != "null";
        };

        var cleanCategories = function (data) {
            var ret = [];

            ret.push({label: "", value: "null", isNullElement: true});

            for (var i = 0; i < data.length; i++) {
                if (data[i].id == "ROOT")
                    continue;
                ret.push({
                    value: data[i].id,
                    label: data[i].title,
                    categoryObj: data[i]
                });
            }
            return ret;
        };

        var loadCategories = function () {

            var readTree = function (data) {
                LoaderService.stop();
                var categories = CategoryUtils.levelTree(data);
                ctrl.categories = cleanCategories(categories);
            };

            LoaderService.start();
            CategoryService.getCategoryTree({from: -1}).$promise.then(readTree, function (error) {
                LoaderService.stop();
                //TODO
            })
        };

        var categoriesFromSession = $sessionStorage["categories"];
        if (!categoriesFromSession)
            loadCategories();

        ctrl.onCategoryChange = function (data) {
            ctrl.selectedCat = data.categoryObj;
        };

        ctrl.filter = {
            categoryId: null
        };

        if (itemViewOpt.fromCategory) {
            ctrl.filter.categoryId = ctrl.selectedCat.id;
        }

        ctrl.clear = function () {
            ctrl.itemsData.items = [];
        };

        var removeFromItems = function (item) {
            var index = ctrl.itemsData.indexOf(item);
            if (index == -1)
                return;

            ctrl.itemsData.splice(index, 1);
        };

        ctrl.search = function () {
            LoaderService.start();

            VoteTypeService.search({categoryId: ctrl.filter.categoryId}).$promise.then(function (data) {

                LoaderService.stop();
                ctrl.itemsData = angular.copy(data);

            }, function (error) {
                LoaderService.stop();
                //TODO
            });
        };

        ctrl.createItem = function () {

            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: WPPathService.getPartialUrl() + "/createVoteTypeTmpl.html",
                controller: "CreateVoteTypeCtrl",
                size: 'sm',
                resolve: {
                    category: function () {
                        return ctrl.selectedCat;
                    },
                    itemsCount: function() {
                        return ctrl.itemsData.total_count;
                    }
                }
            });

            modalInstance.result.then(function (item) {

                LoaderService.start();

                VoteTypeService.create(item).$promise.then(function (ret) {
                    LoaderService.stop();
                    ctrl.search(1);
                    //TODO

                }, function (error) {

                    //TODO
                    LoaderService.stop();

                });

            });

        };

        ctrl.onEdit = function (item) {

            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: WPPathService.getPartialUrl() + "/createVoteTypeTmpl.html",
                controller: "EditVoteTypeCtrl",
                size: 'sm',
                resolve: {
                    item: function () {
                        return VoteTypeService.prepareDBItem(item);
                    },
                    itemsCount: function() {
                        return ctrl.itemsData.total_count;
                    }
                }
            });

            modalInstance.result.then(function (newItem) {

                LoaderService.start();

                VoteTypeService.update(newItem).$promise.then(function (ret) {
                    angular.copy(ret, item);
                    LoaderService.stop();
                    //TODO

                }, function (error) {

                    //TODO
                    LoaderService.stop();

                });

            });

        };

        ctrl.onDelete = function (item) {

            var doDelete = function () {
                LoaderService.start();
                VoteTypeService.delete(item).$promise.then(function (data) {

                    LoaderService.stop();
                    removeFromItems(item);
                    //TODO

                }, function (error) {
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

        if (itemViewOpt.fromCategory)
            ctrl.search();
    }

])

    .controller('EditVoteTypeCtrl', function ($scope, $uibModalInstance, VoteTypeService, item, itemsCount) {

        $scope.mode = "EDIT";
        $scope.data = item;
        $scope.title = "Modifica tipo voto";
        $scope.itemsCount = parseInt(itemsCount);

        $scope.ok = function () {

            if (!VoteTypeService.testEditItem($scope.data)) {
                //TODO
                return;
            }

            var itemDB = VoteTypeService.prepareDBItem($scope.data);
            $uibModalInstance.close(itemDB);
        };

        $scope.cancel = function () {
            $uibModalInstance.dismiss('cancel');
        };

    })

    .controller('CreateVoteTypeCtrl', function ($scope, $uibModalInstance, VoteTypeService, category, itemsCount) {

        $scope.data = {
            id_category: category.id,
            title: "",
            description: "",
            position: undefined,
            vote_limit: 0
        };

        $scope.itemsCount = parseInt(itemsCount);
        $scope.mode = "CREATE";
        $scope.title = "Crea tipo voto";

        $scope.ok = function () {

            if (!VoteTypeService.testCreateItem($scope.data)) {
                //TODO
                return;
            }

            var itemDB = VoteTypeService.prepareDBItem($scope.data);
            $uibModalInstance.close(itemDB);
        };

        $scope.cancel = function () {
            $uibModalInstance.dismiss('cancel');
        };

    });
