angular.module("JRatonUserApp").controller("MainUserCtrl", [ '$scope', 'CONF', 'LoaderService', '$q',
    'CategoryUtils', 'CategoryService',
    function ($scope, CONF, LoaderService, $q, CategoryUtils, CategoryService) {

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

            ret.push({label: "", value: null, isNullElement: true});

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

        var readTree = function (data) {
            LoaderService.stop();

            var nodeObj = CategoryUtils.buildTree(data);
            ctrl.categoryTree = nodeObj;

            var categories = CategoryUtils.levelTree(data);
            ctrl.categories = cleanCategories(categories);
        };

        LoaderService.start();
        CategoryService.getCategoryTree({from: -1}).$promise.then(readTree, function (error) {
            LoaderService.stop();
            //TODO
        });
    }
]);