angular.module("JRatonUserApp").controller("CategoryController", ['$scope', '$location', 'LoaderService', 'WPPathService',
    'ItemService', 'CategoryService', 'CategoryUtils', '$uibModal', '$sessionStorage', 'ModalService', 'CONF',

    function ($scope, $location, LoaderService, WPPathService, ItemService, CategoryService,
              CategoryUtils, $uibModal, $sessionStorage, ModalService, CONF) {

        var ctrl = this;
        ctrl.errorMessage = "";
        ctrl.mainCtrl = $scope.$parent.mainCtrl;

        ctrl.onSelect = function(data) {
            ctrl.mainCtrl.selectedCat = data;
            $location.path("/insertion/" + data.id);
        };

        ctrl.createItem = function() {
            $location.path("/createItem");
        };
    }

]).controller('CreateItemCtrl', function ($scope, $uibModalInstance, CONF, ItemService, userMode, category) {

    $scope.data = {
        id_category: category.id,
        request_approve: 'y',
        approved: 'n'
    };

    $scope.mode = "CREATE";

    $scope.userMode = userMode;
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
