var app = angular.module("JRatonApp", ["treecp","ngResource","ngRoute","ui.bootstrap"]);

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
            otherwise({
                redirectTo: WPPathService.getPartialUrl() + "/adminPage.html"
            });
    }]);

document.addEventListener('DOMContentLoaded', function () {
    angular.bootstrap(document, ["treecp", "ngResource", "ngRoute", "JRatonApp","ui.bootstrap"]);
});
