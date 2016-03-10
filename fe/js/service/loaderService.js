angular.module("JRatonApp").directive("loader", function() {
    return {
        restrict: "EA",
        scope: {},
        template: "<div style='display: none' id='preloader' started='no'></div>"
    }
}).service("LoaderService", function() {

    return {
        start: function() {
            var $ = jQuery;
            var elem = $('#preloader');
            if(elem.attr("started") === "yes")
                return;

            elem.attr("started","yes");
            elem.fadeIn('slow');
        },

        stop: function() {
            var $ = jQuery;
            var elem = $('#preloader');
            if(elem.attr("started") != "yes")
                return;

            elem.attr("started","no");
            elem.fadeOut('slow');
        }
    }

});
