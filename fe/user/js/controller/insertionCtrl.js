angular.module("JRatonUserApp").controller("insertionCtrl", [ '$scope', 'CONF', 'LoaderService', '$q',
    function ($scope, CONF, LoaderService, $q) {

        var ctrl = this;
        ctrl.mainCtrl = $scope.$parent.mainCtrl;

        ctrl.title = ctrl.mainCtrl.selectedCat.parentChain.join(" » ");

    }]
);