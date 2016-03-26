angular.module("JRatonApp").controller("MainAdminCtrl", [ 'CONF', 'LoaderService', '$q',
    'CategoryUtils', 'CategoryService',
    function (CONF, LoaderService, $q, CategoryUtils, CategoryService) {

        var ctrl = this;
        ctrl.CONF = CONF;

        ctrl.dateOptions = {
            formatYear: 'yyyy',
            maxDate: new Date(2020, 12, 12),
            minDate: new Date(2016, 1, 1),
            startingDay: 1
        };

        ctrl.state_approve_items = [
            {label: "Tutti", value: 'a'},
            {label: "In attesa", value: 'y'},
            {label: "Già approvati", value: 'n'}
        ];

        ctrl.time_items = [
            {label: "", value: "null", isNullElement: true},
            {label: "Prima del", value: 'before'},
            {label: "Dopo il", value: 'after'},
            {label: "Il", value: 'at'}
        ];

        ctrl.enable_state_items = [
            {label: "Tutti", value: 'a'},
            {label: "Approvati", value: 'y'},
            {label: "Non approvati", value: 'n'}
        ];

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
                var categories = CategoryUtils.levelTree(data);
                var categories = cleanCategories(categories);
                deferred.resolve(categories);
            };

            LoaderService.start();
            CategoryService.getCategoryTree({from: -1}).$promise.then(readTree, function (error) {
                LoaderService.stop();
                //TODO
            });

            return deferred.promise;
        };
    }
]);