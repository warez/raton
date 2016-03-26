angular.module("JRatonCommon").service("CategoryResource", ['$resource', function ($resource) {

    var resource = WP_API_Settings.root + "?rest_route=/raton/v1_0/category";

    return $resource( resource + "/:id", {id:'@id'}, {

        getCategoryTree: {url: resource + '/tree/:from' , method: 'GET' , params: {}, isArray: false},

        delete: {method: 'DELETE', params: {}, isArray: true},

        get: {method: 'GET', params: {}, isArray: false},

        update: {method: 'PUT', params: {}, isArray: false},

        create: {method: 'POST', params: {}, isArray: false}

    });

    return resource;

}]);