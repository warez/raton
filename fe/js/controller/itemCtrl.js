angular.module("JRatonApp").controller("ItemController", ['$scope', '$location', 'LoaderService', 'WPPathService',
    'CategoryUtils', 'ItemService', '$uibModal', '$sessionStorage', 'ModalService',

    function ($scope, $location, LoaderService, WPPathService, CategoryUtils, ItemService, $uibModal,
              $sessionStorage, ModalService) {

        var ctrl = this;

        ctrl.selectedCat = $sessionStorage["category"];
        ctrl.itemsData = {
            page: 1,
            per_page: 10
        };

        ctrl.onEdit = function(item) {

        };

        ctrl.onDelete = function(item) {
            var doDelete = function() {
                //LoaderService.start();
            };

            var modalOptions = {
                closeButtonText: 'Annulla',
                actionButtonText: "Cancella!",
                headerText: "Attenzione",
                bodyText: "Sei sicuro di voler cancellare l'articolo?"
            };

            ModalService.showModal({}, modalOptions).then(function () {
                doDelete();
            });

        };

        ctrl.load = function() {
            LoaderService.start();
            ItemService.getFromCategory(ctrl.selectedCat.id,
                ctrl.itemsData.page,
                ctrl.itemsData.per_page).$promise.then(
                function(data) {
                    LoaderService.stop();
                    ctrl.itemsData = angular.copy(data);
                }, function(error) {
                    LoaderService.stop();
                    //TODO
                }
            );
        };

        ctrl.load();

        ctrl.pageChanged = function() {
            ctrl.load();
        }
    }

]);