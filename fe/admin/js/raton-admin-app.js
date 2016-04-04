var app = angular.module("JRatonApp", ["treecp","ngResource","ngRoute","ui.bootstrap", "ngStorage", "JRatonCommon"]);

app.config(['$resourceProvider','$httpProvider', function($resourceProvider, $httpProvider) {
    // Don't strip trailing slashes from calculated URLs
    //$resourceProvider.defaults.stripTrailingSlashes = false;
    $httpProvider.defaults.headers.common["X-WP-Nonce"] = WP_API_Settings.nonce;
}]);


app.config(['$routeProvider', 'WPPathServiceProvider',
    function($routeProvider, WPPathServiceProvider) {

        var WPPathService = WPPathServiceProvider.$get();

        $routeProvider.
            when('/category', {
                templateUrl: WPPathService.getPartialUrl() + "/category-view.html",
                controller: 'CategoryController',
                controllerAs: 'ctrl'
            }).
            when('/items', {
                templateUrl: WPPathService.getPartialUrl() + "/item-view.html",
                controller: 'ItemController',
                controllerAs: 'ctrl',
                resolve: {
                    itemViewOpt: function() {
                        return {
                            fromCategory: false
                        };
                    }
                }
            }).
            when('/item/:idItem/reviews', {
                templateUrl: WPPathService.getPartialUrl() + "/review-view.html",
                controller: 'ReviewController',
                controllerAs: 'ctrl',
                resolve: {
                    itemViewOpt: function() {
                        return {}
                    }
                }
            }).
            when('/category/:idCategory/items', {
                templateUrl: WPPathService.getPartialUrl() + "/item-view.html",
                controller: 'ItemController',
                controllerAs: 'ctrl',
                resolve: {
                    itemViewOpt: function() {
                        return {
                            fromCategory: true
                        }
                    }
                }
            }).
            when('/voteType', {
                templateUrl: WPPathService.getPartialUrl() + "/voteType-view.html",
                controller: 'VoteTypeController',
                controllerAs: 'ctrl',
                resolve: {
                    itemViewOpt: function() {
                        return {
                            fromCategory: false
                        };
                    }
                }
            }).
            when('/category/:idCategory/voteType', {
                templateUrl: WPPathService.getPartialUrl() + "/voteType-view.html",
                controller: 'VoteTypeController',
                controllerAs: 'ctrl',
                resolve: {
                    itemViewOpt: function() {
                        return {
                            fromCategory: true
                        }
                    }
                }
            }).
            otherwise({
                redirectTo: "/category"
            });
    }]);

document.addEventListener('DOMContentLoaded', function () {
    angular.bootstrap(document, ["treecp", "ngStorage", "ngResource", "ngRoute", "JRatonApp","ui.bootstrap", "JRatonCommon"]);
});
