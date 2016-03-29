angular.module("JRatonUserApp").controller("insertionCtrl", [ '$scope', 'CONF', 'LoaderService', '$uibModal', 'ItemService', 'WPPathService',
    function ($scope, CONF, LoaderService, $uibModal, ItemService, WPPathService) {

        var ctrl = this;
        ctrl.mainCtrl = $scope.$parent.mainCtrl;

        ctrl.title = ctrl.mainCtrl.selectedCat.parentChain.join(" » ");

    }]
).controller("createItemCtrl", [ '$scope', 'CONF', 'LoaderService', '$uibModal', 'ItemService', 'WPPathService',

    function ($scope, CONF, LoaderService, $uibModal, ItemService, WPPathService) {

        var ctrl = this;
        ctrl.mainCtrl = $scope.$parent.mainCtrl;

    }]
);