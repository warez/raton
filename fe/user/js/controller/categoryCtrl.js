angular.module("JRatonUserApp").controller("CategoryController", ['$scope', '$location', 'LoaderService', 'WPPathService',
    'CategoryService', '$uibModal', '$sessionStorage', 'ModalService', 'CONF',

    function ($scope, $location, LoaderService, WPPathService, CategoryService, $uibModal, $sessionStorage, ModalService, CONF) {

        var ctrl = this;
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

]);
