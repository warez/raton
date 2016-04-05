angular.module("JRatonCommon").service("ReviewResource", ['$resource', function ($resource) {

    var resource = WP_API_Settings.root + "?rest_route=/raton/v1_0/review";

    return $resource( resource + "/:id", {id:'@id'}, {

        delete: {method: 'DELETE', params: {}, isArray: true},

        get: {method: 'GET', params: {}, isArray: false},

        update: {method: 'PUT', params: {}, isArray: false},

        create: {method: 'POST', params: {}, isArray: false},

        search: {url: resource + '/search/byItem/:idItem', method: 'POST', params: {idItem:'@idItem'}, isArray: false},

    });

    return resource;

}]);