angular.module("JRatonApp").service("CONF", [function () {

    var data = {
        WP_SETTINGS : WP_API_Settings
    };

    return data;

}]).provider('WPPathService', function WPPathServiceProvider() {

    this.$get = function WPPathServiceFactory() {
        return {
            getPartialUrl: function () {
                return WP_API_Settings.RATON_FE_URL + "/partial";
            }
        };
    };
}).service('ModalService', ["$uibModal", function ModalService($uibModal) {

    return {
        open: function (template, controller, data, callback) {
            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: template,
                controller: controller,
                size: 'lg',
                resolve: {
                    data: function () {
                        return data
                    }
                }
            });

            modalInstance.result.then(function () {
                callback( {result:result} );
            });
        }
    };

}]);
