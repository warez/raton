var app = angular.module("JRatonUserApp", ["treecp","ngResource","ngRoute","ui.bootstrap", "ngStorage", "JRatonCommon"]);

app.config(['$resourceProvider','$httpProvider', function($resourceProvider, $httpProvider) {
    // Don't strip trailing slashes from calculated URLs
    //$resourceProvider.defaults.stripTrailingSlashes = false;
    $httpProvider.defaults.headers.common["X-WP-Nonce"] = WP_API_Settings.nonce;
}]);


app.config(['$routeProvider', 'WPPathServiceProvider',
    function($routeProvider, WPPathServiceProvider) {

        var WPPathService = WPPathServiceProvider.$get();

        $routeProvider.
            when('/insertion/:id', {
                templateUrl: WPPathService.getPartialUrl() + "/insertion-view.html",
                controller: 'insertionCtrl',
                controllerAs: 'ctrl'
            }).
            when('/home', {
                templateUrl: WPPathService.getPartialUrl() + "/home-view.html",
                controller: 'homeCtrl',
                controllerAs: 'ctrl'
            }).
            otherwise({
                redirectTo: "/home"
            });

    }]);

document.addEventListener('DOMContentLoaded', function () {
    angular.bootstrap(document, ["treecp", "ngStorage", "ngResource", "ngRoute", "JRatonUserApp","ui.bootstrap","JRatonCommon"]);
});
