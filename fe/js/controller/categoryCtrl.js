angular.module("JRatonApp").controller("CategoryController", ['$scope', '$location', 'LoaderService', 'WPPathService',
    'CategoryUtils', 'CategoryService', '$uibModal', '$sessionStorage', 'ModalService', 'CONF',

    function ($scope, $location, LoaderService, WPPathService, CategoryUtils, CategoryService, $uibModal, $sessionStorage, ModalService, CONF) {

        var ctrl = this;
        ctrl.mainCtrl = $scope.$parent.mainCtrl;

        ctrl.onShowItem = function(cat) {
            var catCopy = CategoryUtils.copyCategory(cat);

            $sessionStorage["category"] = catCopy;
            $location.path('/category/' + catCopy.id + "/items");
        };

        ctrl.onShowVoteType = function(cat) {
            var catCopy = CategoryUtils.copyCategory(cat);

            $sessionStorage["category"] = catCopy;
            $location.path('/category/' + catCopy.id + "/voteType");
        };

        ctrl.onDelete = function (cat) {

            if(CategoryService.testDeleteCategory(cat)) {
                return; //TODO
            }

            var doDelete = function() {

                LoaderService.start();
                CategoryService.delete(cat).$promise.then(function (category) {

                    LoaderService.stop();
                    CategoryUtils.onDelete(cat);
                    //TODO

                }, function (error) {

                    //TODO
                    LoaderService.stop();

                });
            };

            var modalOptions = {
                closeButtonText: 'Annulla',
                actionButtonText: "Cancella!",
                headerText: "Attenzione",
                bodyText: "Sei sicuro di voler cancellare la categoria?"
            };

            ModalService.showModal({}, modalOptions).then(function () {
                doDelete();
            });

        };

        ctrl.onMove = CategoryUtils.onMove;

        ctrl.onEdit = function (cat) {

            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: WPPathService.getPartialUrl() + "/createCategoryTmpl.html",
                controller: "EditCategoryCtrl",
                size: 'sm',
                resolve: {
                    category: function () {
                        return CategoryUtils.copyCategory(cat);
                    }
                }
            });

            modalInstance.result.then(function (categoryMod) {

                LoaderService.start();

                CategoryService.update( categoryMod).$promise.then(function (ret) {
                    CategoryUtils.onEdit(cat, ret);
                    LoaderService.stop();
                    //TODO

                }, function (error) {

                    //TODO
                    LoaderService.stop();

                });

            });
        };

        ctrl.onDeleteChildren = CategoryUtils.onDeleteChildren;

        ctrl.onAdd = function (parent) {

            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: WPPathService.getPartialUrl() + "/createCategoryTmpl.html",
                controller: "CreateCategoryCtrl",
                size: 'sm',
                resolve: {
                    parent: function () {
                        return parent;
                    }
                }
            });

            modalInstance.result.then(function (category) {

                LoaderService.start();

                CategoryService.create(category).$promise.then(function (ret) {
                    CategoryUtils.onAdd(parent, ret);
                    LoaderService.stop();

                }, function (error) {

                    //TODO
                    LoaderService.stop();

                });

            });
        };

        ctrl.errorMessage = "";

        var buildTreeFromCategoryTree = function (data) {

            LoaderService.stop();

            var nodeObj = CategoryUtils.buildTree(data);
            ctrl.treeData = angular.copy(nodeObj);
        };

        LoaderService.start();
        CategoryService.getCategoryTree({from: -1}).$promise.then(
            buildTreeFromCategoryTree, function (error) {

                LoaderService.stop();
            }
        );
    }

]).controller('CreateCategoryCtrl', function ($scope, $uibModalInstance, CategoryService, parent) {

    $scope.data = {
        title: "",
        description: ""
    };

    $scope.title = "Crea nuova categoria";
    $scope.ok = function () {

        if (!CategoryService.testNewCategory($scope.data))
            return;

        var catDB = CategoryService.prepareDBCategory($scope.data, parent);
        $uibModalInstance.close(catDB);
    };

    $scope.cancel = function () {
        $uibModalInstance.dismiss('cancel');
    };

}).controller('EditCategoryCtrl', function ($scope, $uibModalInstance, CategoryUtils, CategoryService, category) {

    $scope.data = category;

    $scope.title = "Modifica categoria";
    $scope.ok = function () {

        if (!CategoryService.testEditCategory($scope.data)) {
            //TODO
            return;
        }

        var catDB = CategoryService.prepareDBCategory($scope.data, category.parent);
        $uibModalInstance.close(catDB);
    };

    $scope.cancel = function () {
        $uibModalInstance.dismiss('cancel');
    };

});
