angular.module("JRatonUserApp").controller("CategoryController", ['$scope', '$location', 'LoaderService', 'WPPathService',
    'CategoryService', 'CategoryUtils', '$uibModal', '$sessionStorage', 'ModalService', 'CONF',

    function ($scope, $location, LoaderService, WPPathService, CategoryService,
              CategoryUtils, $uibModal, $sessionStorage, ModalService, CONF) {

        var ctrl = this;
        ctrl.errorMessage = "";
        ctrl.mainCtrl = $scope.$parent.mainCtrl;

        ctrl.onSelect = function(data) {
            ctrl.mainCtrl.selectedCat = data;
            $location.path("/insertion/" + data.id);
        };
    }

]);
