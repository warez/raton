var app = angular.module("JRatonUserApp", ["treecp","ngResource","ngRoute","ui.bootstrap", "ngStorage", "raton-common"]);

app.config(['$resourceProvider','$httpProvider', function($resourceProvider, $httpProvider) {
    // Don't strip trailing slashes from calculated URLs
    //$resourceProvider.defaults.stripTrailingSlashes = false;
    $httpProvider.defaults.headers.common["X-WP-Nonce"] = WP_API_Settings.nonce;
}]);


app.config(['$routeProvider', 'WPPathServiceProvider',
    function($routeProvider, WPPathServiceProvider) {

        var WPPathService = WPPathServiceProvider.$get();

    }]);

document.addEventListener('DOMContentLoaded', function () {
    angular.bootstrap(document, ["treecp", "ngStorage", "ngResource", "ngRoute", "JRatonUserApp","ui.bootstrap","raton-common"]);
});
