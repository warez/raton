var phonecatApp = angular.module('ratonAdminApp', ["ngResource"]);

phonecatApp.factory('ItemResource', ['$resource',

    function ($resource) {

        var resource = WP_API_Settings.root + "?rest_route=/raton/v1_0/";

        return $resource( resource + 'item/:itemId', {}, {

            getAll: {method: 'GET', params: {itemId: 'all'}, isArray: true},

            getItem: {method: 'GET', params: {}, isArray: false}

        });
    }

]);

phonecatApp.controller('adminMainCtrl', function (ItemResource, $http) {

    var ctrl = this;
    $http.defaults.headers.common["X-WP-Nonce"] = WP_API_Settings.nonce;

    this.error = {};
    this.searchItem = "";
    this.item = {};
    this.items = [];

    this.searchPost = function () {

        ItemResource.getItem( { itemId : ctrl.searchItem } ).$promise.then(function(data) {
            ctrl.item = data;
        }, function(error) {
            ctrl.error = error;
        });

    };

});