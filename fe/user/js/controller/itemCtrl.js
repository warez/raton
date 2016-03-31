angular.module("JRatonUserApp").controller("insertionCtrl", [ '$scope', 'CONF', 'LoaderService', '$uibModal', 'ItemService', 'WPPathService',
    function ($scope, CONF, LoaderService, $uibModal, ItemService, WPPathService) {

        var ctrl = this;
        ctrl.mainCtrl = $scope.$parent.mainCtrl;

        ctrl.title = ctrl.mainCtrl.selectedCat.parentChain.join(" » ");

    }]
).controller("createItemCtrl", [ '$scope', 'CONF', 'LoaderService', '$uibModal', 'ItemService', 'WPPathService',

    function ($scope, CONF, LoaderService, $uibModal, ItemService, WPPathService) {

        var ctrl = this;
        ctrl.mainCtrl = $scope.mainCtrl;

        ctrl.message = {
            visible: false,
            msg: "",
            clazz: ""
        };

        ctrl.dismissAlert = function() {
            ctrl.message = {
                visible: false,
                msg: "",
                clazz: ""
            };
        };

        ctrl.resetFilter = function() {
            ctrl.filter = {
                title: "",
                description: "",
                category: null
            };
        };

        ctrl.addNewInsertion = function() {

            if($scope.insertArticleForm.$invalid)
                return;

            var ret = {};

            ret.title = ctrl.filter.title.trim();
            ret.description = ctrl.filter.description.trim();
            ret.id_category = ctrl.filter.category;
            ret.approved = false;
            ret.request_approve = true;

            ItemService.create(ret).$promise.then(function(data) {

                ctrl.message = {
                    visible: true,
                    msg: "La categoria è in fase di approvazione. Grazie per il tuo contributo.",
                    clazz: "alert-success"
                };
                ctrl.resetFilter();

            }, function(error) {

                ctrl.message = {
                    visible: true,
                    msg: "Errore nella creazione della categoria. Riprova in seguito," +
                        " e se il problema persiste contatta un amministratore.",
                    clazz: "alert-danger"
                };

            });

            ctrl.resetFilter();

        };

    }]
);