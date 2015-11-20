var phonecatApp = angular.module('ratonAdminApp', ["ngResource"]);

phonecatApp.config(['$resourceProvider', function($resourceProvider) {
    // Don't strip trailing slashes from calculated URLs
    //$resourceProvider.defaults.stripTrailingSlashes = false;
}]);

phonecatApp.factory('ItemResource', ['$resource',

    function ($resource) {

        var resource = WP_API_Settings.root + "?rest_route=/raton/v1_0/";

        return $resource( resource + ':res/:func/:param', {}, {

            delete: {method: 'DELETE', params: {}, isArray: false},

            get: {method: 'GET', params: {}, isArray: false},

            update: {method: 'PUT', params: {}, isArray: false},

            create: {method: 'POST', params: {}, isArray: false},

        });
    }

]);

phonecatApp.controller('adminMainCtrl', function (ItemResource, $http) {

    var ctrl = this;
    $http.defaults.headers.common["X-WP-Nonce"] = WP_API_Settings.nonce;

    this.error = {};
    this.ret = {};

    this.resource = "";
    this.func = "";

    this.itemBodyJson = "{}";
    this.itemId = 0;

    function clear() {
        ctrl.error = "";
        ctrl.ret = "";
    }

    function printResponse (data) {

        if(!data[0]) {
            ctrl.ret = data;
            return;
        }

        var ret = "";

        for(var i = 0; i<17000; i++ ) {
            ret += data[i];
        }

        ctrl.ret = ret;
    }

    this.search = function () {

        clear();

        ItemResource.get( { res: ctrl.resource, func: ctrl.func, param : ctrl.itemId } ).$promise.then(function(data) {

            printResponse(data);

        }, function(error) {

            ctrl.error = error;

        });

    };

    this.create = function () {

        clear();

        var obj = angular.fromJson(ctrl.itemBodyJson);

        ItemResource.create( { func: ctrl.resource }, obj ).$promise.then(function(data) {

            printResponse(data);

        }, function(error) {

            ctrl.error = error;

        });

    };

    this.update = function () {

        clear();

        var obj = angular.fromJson(ctrl.itemBodyJson);

        ItemResource.update( { func: ctrl.resource, param : ctrl.itemId  }, obj ).$promise.then(function(data) {

            printResponse(data);

        }, function(error) {

            ctrl.error = error;
        });

    };

    this.delete = function () {

        clear();

        ItemResource.delete( { func: ctrl.resource, param : ctrl.itemId } ).$promise.then(function(data) {
            printResponse(data);
        }, function(error) {
            ctrl.error = error;
        });

    };

});