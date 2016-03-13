// © Copyright 2014 Thinkster.
// Shamelessly stolen from
//   http://www.thinkster.io/pick/JCDDOCEwVX/angularjs-building-zippy
//
angular.module("JRatonApp").directive("categoryComponent", function(WPPathService){
  return {
    restrict: "E",
    transclude: true,
    scope: {
      data: "=",
      onAdd: "&",
      onDelete: "&",
      onShowItem: "&",
      onShowFilter: "&",
      onEdit: "&",
      onMove: "&",
      enableMove:"=",
      expandButtonClass:"@",
      collapseButtonClass:"@"
    },
    templateUrl: function(elem, attrs) {
        return WPPathService.getPartialUrl() + "/" + attrs.template;
    },

    link: function(scope){

      scope.isContentVisible = true;
      scope.parent = null;

      scope.showMoveUp = function(){
        if(!scope.data || !scope.data.parent)
          return;

        return scope.data.parent.children &&
            scope.data.parent.children.indexOf(scope.data) > 0;
      };

      scope.showMoveDown = function(){
        if(!scope.data || !scope.data.parent)
          return;

        return scope.data.parent.children &&
          scope.data.parent.children.indexOf(scope.data) !=
            scope.data.parent.children.length - 1;
      };

      scope.getClass = function() {

        if(!scope.data || !scope.data.children || scope.data.children.length == 0)
          return "";
        else if(scope.isContentVisible)
          return scope.collapseButtonClass;
        else
          return scope.expandButtonClass;
      };

      scope.toggleContent = function(){
        scope.isContentVisible = !scope.isContentVisible;
      };

    }
  };
}).directive("bootstrapSelect", function(WPPathService, $parse) {
  return {
    restrict: "E",
    scope: {
      items: "=",
      val: "=",
    },
    templateUrl: function () {
      return WPPathService.getPartialUrl() + "/bootstrap-select.html";
    },
    controller: function($scope, $element, $attrs) {

      var ctrl = this;
      this.selectedIndex = 0;

      this.select = function(index) {

        ctrl.selectedIndex = index;
        ctrl.val = ctrl.items[index].value;
      };

      $scope.$watch('ctrl.val', function(newValue, oldValue) {
        if (newValue) {
          for(var i = 0; i < ctrl.items.length; i++) {
            if(ctrl.items[i].value == newValue) {
              ctrl.select(i);
              break;
            }
          }
        }
      }, false);

    },
    controllerAs: 'ctrl',
    bindToController: true

  }
}).filter("toDate", function() {
  return function(input) {

    if(!input)
      return null;

    if(angular.isNumber(input)) {
      input = "" + input;
    }

    var year = input.substr(0,4);
    var month = input.substr(4,2);
    var day = input.substr(6,2);
    var hh = input.substr(8,2);
    var minute = input.substr(10,2);
    var sec = input.substr(12,2);

    var date = new Date(year, month - 1, day, hh, minute, sec, 0);
    return date;

  };
}).filter("fromDate", function() {
  return function(input) {

    if(!input)
      return null;

    if(typeof input.getMonth !== 'function')
      return null;

    var year = input.getFullYear();
    var hh = input.getHours();
    var day = input.getDate();
    var minute = input.getMinutes();
    var month = input.getMonth() + 1;
    var sec = input.getSeconds();

    var date = "" + year +
        ((month < 10) ? "0" : "") + month +
        ((day < 10) ? "0" : "") + day +
        ((hh < 10) ? "0" : "") + hh +
        ((minute < 10) ? "0" : "") + minute +
        ((sec < 10) ? "0" : "") + sec;

    return parseInt(date, 10);
  };
});