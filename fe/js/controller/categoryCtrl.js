angular.module("JRatonApp").controller("CategoryController", ['$scope', 'LoaderService', 'WPPathService', 'CategoryUtils', 'CategoryService', '$uibModal', 'CONF',

    function ($scope, LoaderService, WPPathService, CategoryUtils, CategoryService, $uibModal, CONF) {

        var ctrl = this;

        ctrl.onDelete = function (cat) {

            if(CategoryService.testDeleteCategory(cat)) {
                return; //TODO
            }

            LoaderService.start();
            CategoryService.delete(cat).$promise.then(function (category) {

                LoaderService.stop();
                CategoryUtils.onDelete(cat);

            }, function (error) {

                //TODO
                LoaderService.stop();

            });
        }

        ctrl.onMove = CategoryUtils.onMove;

        ctrl.onEdit = function (cat) {

            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: WPPathService.getPartialUrl() + "/createCategoryTmpl.html",
                controller: "EditCategoryCtrl",
                size: 'sm',
                resolve: {
                    category: function () {
                        return cat;
                    }
                }
            });

            modalInstance.result.then(function (category) {

                LoaderService.start();

                CategoryService.update( category).$promise.then(function (ret) {
                    CategoryUtils.onEdit(category, ret);
                    LoaderService.stop();

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

        this.treeData = {};

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

    $scope.ok = function () {

        if (!CategoryService.testNewCategory($scope.data))
            return;

        var catDB = CategoryService.prepareDBCategory($scope.data, parent);
        $uibModalInstance.close(catDB);
    };

    $scope.cancel = function () {
        $uibModalInstance.dismiss('cancel');
    };

}).controller('EditCategoryCtrl', function ($scope, $uibModalInstance, CategoryService, category) {

    $scope.data = category;

    $scope.ok = function () {

        if (!CategoryService.testEditCategory($scope.data))
            return;

        var catDB = CategoryService.prepareDBCategory($scope.data, category.parent);
        $uibModalInstance.close(catDB);
    };

    $scope.cancel = function () {
        $uibModalInstance.dismiss('cancel');
    };

});
