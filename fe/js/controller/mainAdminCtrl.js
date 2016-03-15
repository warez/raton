angular.module("JRatonApp").controller("MainAdminCtrl", [ 'CONF',
    function (CONF) {

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

    }
]);