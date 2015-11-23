var phonecatApp = angular.module('ratonAdminApp', ["ngResource"]);

phonecatApp.config(['$resourceProvider', function($resourceProvider) {
    // Don't strip trailing slashes from calculated URLs
    //$resourceProvider.defaults.stripTrailingSlashes = false;
}]);

phonecatApp.controller('adminMainCtrl', function ($resource, $http) {

    var ctrl = this;
    $http.defaults.headers.common["X-WP-Nonce"] = WP_API_Settings.nonce;

    this.error = {};
    this.ret = {};

    this.resource = "";

    this.itemBodyJson = "{}";
    this.itemId = 0;

    function clear() {
        ctrl.error = "";
        ctrl.ret = "";
    }

    function getResource() {
        var resource = WP_API_Settings.root + "?rest_route=/raton/v1_0/" + ctrl.query;

        return $resource( resource, {}, {

            delete: {method: 'DELETE', params: {}, isArray: true},

            get: {method: 'GET', params: {}, isArray: false},

            getArray: {method: 'GET', params: {}, isArray: true},

            update: {method: 'PUT', params: {}, isArray: false},

            create: {method: 'POST', params: {}, isArray: false},

        });

        return resource;
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

        getResource().get({}).$promise.then(function(data) {

            printResponse(data);

        }, function(error) {

            ctrl.error = error;

        });

    };

    this.searchArray = function () {

        clear();

        getResource().getArray({}).$promise.then(function(data) {

            printResponse(data);

        }, function(error) {

            ctrl.error = error;

        });

    };

    this.create = function () {

        clear();

        var obj = angular.fromJson(ctrl.itemBodyJson);

        getResource().create( {}, obj ).$promise.then(function(data) {

            printResponse(data);

        }, function(error) {

            ctrl.error = error;

        });

    };

    this.update = function () {

        clear();

        var obj = angular.fromJson(ctrl.itemBodyJson);

        getResource().update( {}, obj ).$promise.then(function(data) {

            printResponse(data);

        }, function(error) {

            ctrl.error = error;
        });

    };

    this.delete = function () {

        clear();

        getResource().delete( {} ).$promise.then(function(data) {

            printResponse(data);

        }, function(error) {
            ctrl.error = error;
        });

    };

});