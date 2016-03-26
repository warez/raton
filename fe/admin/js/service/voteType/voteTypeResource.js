angular.module("JRatonApp").service("VoteTypeResource", ['$resource', function ($resource) {

    var resource = WP_API_Settings.root + "?rest_route=/raton/v1_0/voteType";

    return $resource( resource + "/:id", {id:'@id'}, {

        delete: {method: 'DELETE', params: {}, isArray: true},

        get: {method: 'GET', params: {}, isArray: false},

        update: {method: 'PUT', params: {}, isArray: false},

        create: {method: 'POST', params: {}, isArray: false},

        search: {url: resource + '/search/byCategory/:categoryId', method: 'GET', params: {}, isArray: false},

        move: {url: resource + '/move', method: 'POST', params: {}, isArray: false}

    });

    return resource;

}]);