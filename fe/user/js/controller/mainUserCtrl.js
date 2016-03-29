angular.module("JRatonUserApp").controller("MainUserCtrl", [ 'CONF', 'LoaderService', '$q',
    'CategoryUtils', 'CategoryService',
    function (CONF, LoaderService, $q, CategoryUtils, CategoryService) {

        var ctrl = this;
        ctrl.CONF = CONF;

        ctrl.userMode = CONF.WP_SETTINGS["userMode"];
        ctrl.isNotLogged = !ctrl.userMode || ctrl.userMode == "GUEST";

        ctrl.categoryTree = {};
        ctrl.categories = [];
        ctrl.selectedCat = {};

        ctrl.dateOptions = {
            formatYear: 'yyyy',
            maxDate: new Date(2020, 12, 12),
            minDate: new Date(2016, 1, 1),
            startingDay: 1
        };

        var cleanCategories = function (data) {
            var ret = [];

            ret.push({label: "", value: "null", isNullElement: true});

            for (var i = 0; i < data.length; i++) {
                if (data[i].id == "ROOT")
                    continue;
                ret.push({
                    value: data[i].id,
                    label: data[i].title,
                    categoryObj: data[i]
                });
            }
            return ret;
        };

        ctrl.loadCategories = function () {

            var deferred = $q.defer();

            var readTree = function (data) {
                LoaderService.stop();

                var nodeObj = CategoryUtils.buildTree(data);
                ctrl.categoryTree = angular.copy(nodeObj);

                var categories = CategoryUtils.levelTree(data);
                ctrl.categories = cleanCategories(categories);
                deferred.resolve( {} );
            };

            LoaderService.start();
            CategoryService.getCategoryTree({from: -1}).$promise.then(readTree, function (error) {
                LoaderService.stop();
                //TODO
            });

            return deferred.promise;
        };

        ctrl.loadCategories();
    }
]);