angular.module("JRatonApp").service("ItemResource", ['$resource', function ($resource) {

    var resource = WP_API_Settings.root + "?rest_route=/raton/v1_0/item";

    return $resource( resource + "/:id", {id:'@id'}, {

        getFromCategory: {url: resource + '/fromCategory/:from' , method: 'GET' , params: {}, isArray: false},

        delete: {method: 'DELETE', params: {}, isArray: true},

        get: {method: 'GET', params: {}, isArray: false},

        update: {method: 'PUT', params: {}, isArray: false},

        create: {method: 'POST', params: {}, isArray: false}

    });

    return resource;

}]);