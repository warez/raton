angular.module("JRatonCommon").service("CONF", [function () {

    var data = {
        WP_SETTINGS : WP_API_Settings,
        DATE_TIME_FORMAT: "dd/MM/yyyy HH:mm:ss",
        DATE_FORMAT: "dd/MM/yyyy"
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
}).service('ModalService', ["$uibModal", "WPPathService",
    function ($uibModal, WPPathService) {

    var modalDefaults = {
        backdrop: true,
        keyboard: true,
        modalFade: true,
        templateUrl: WPPathService.getPartialUrl() + "/dialogTemplate.html"
    };

    var modalOptions = {
        closeButtonText: 'Close',
        actionButtonText: 'OK',
        headerText: 'Proceed?',
        bodyText: 'Perform this action?'
    };

    return {

        showModal: function (customModalDefaults, customModalOptions) {

            if (!customModalDefaults)
                customModalDefaults = {};

            customModalDefaults.backdrop = 'static';
            return this.show(customModalDefaults, customModalOptions);
        },

        show: function (customModalDefaults, customModalOptions) {
            //Create temp objects to work with since we're in a singleton service
            var tempModalDefaults = {};
            var tempModalOptions = {};

            //Map angular-ui modal custom defaults to modal defaults defined in service
            angular.extend(tempModalDefaults, modalDefaults, customModalDefaults);

            //Map modal.html $scope custom properties to defaults defined in service
            angular.extend(tempModalOptions, modalOptions, customModalOptions);

            if (!tempModalDefaults.controller) {
                tempModalDefaults.controller = function ($scope, $uibModalInstance) {
                    $scope.modalOptions = tempModalOptions;
                    $scope.modalOptions.ok = function (result) {
                        $uibModalInstance.close(result);
                    };
                    $scope.modalOptions.close = function (result) {
                        $uibModalInstance.dismiss('cancel');
                    };
                }
            }

            return $uibModal.open(tempModalDefaults).result;
        },

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
                callback({result: result});
            });
        }
    };

}]).service("ErrorMesssageUtils", function() {

    return {

        printError: function(obj) {
            var ret = "", index = 0;
            while(true) {
                if(!obj[index])
                    return ret;

                ret += obj[index];
                index++;
            }

            return ret;
        }

    }

});