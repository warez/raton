angular.module("JRatonUserApp").directive("categoryListComponent", function(WPPathService){

  return {
    restrict: "E",
    controller: function () {

      var ctrl = this;
      this.selectedId = null;

      this.select = function (selectedId) {
        ctrl.selectedId = selectedId;
      };
    }
  }

}).directive("categoryComponent", function(WPPathService, CategoryService){
  return {
    restrict: "E",
    transclude: true,
    require: "^categoryListComponent",
    scope: {
      data: "=",
      onSelect: "&",
      expandButtonClass:"@",
      collapseButtonClass:"@"
    },
    templateUrl: function(elem, attrs) {
        return WPPathService.getPartialUrl() + "/" + attrs.template;
    },

    link: function(scope, element, attrs, categoryListCtrl){

      scope.isContentVisible = scope.data.id == 'ROOT';

      scope.categoryListCtrl = categoryListCtrl;

      scope.$watch('data', function(newValue, oldValue) {
        if (newValue)
          scope.hasChildren = newValue.children && newValue.children.length > 0;
      }, false);

      scope.select = function() {

        if(!scope.hasChildren) {

          categoryListCtrl.select(scope.data.id);

          if(attrs["onSelect"]) {
            var selectedData = CategoryService.prepareDBCategory(scope.data, scope.data.parent);
            selectedData.parentChain = CategoryService.getParentChain(scope.data);
            scope.onSelect({data: selectedData});
          }

        }

        if(scope.hasChildren)
          scope.isContentVisible = !scope.isContentVisible;
      };

      scope.getStyle = function() {
        if(scope.data.id == 'ROOT' || (scope.data.parent && scope.data.parent.id == 'ROOT') )
          return "";

        return "margin-left: 15px";
      };

      scope.getClass = function() {

        if(!scope.hasChildren)
          return "";
        else if(scope.isContentVisible)
          return scope.collapseButtonClass;
        else
          return scope.expandButtonClass;
      };

    }
  };
}).directive("bootstrapSelect", function(WPPathService, $parse) {
  return {
    restrict: "E",
    scope: {
      items: "=",
      val: "=",
      onSelect: "&"
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

        if(ctrl.onSelect)
          ctrl.onSelect({data:ctrl.items[index]});
      };

      var doSelect = function(value) {
        for(var i = 0; i < ctrl.items.length; i++) {
          if(ctrl.items[i].value == value) {
            ctrl.select(i);
            break;
          }
        }
      };

      $scope.$watch('ctrl.items', function(newValue, oldValue) {
        if (newValue && ctrl.val) {
          doSelect(ctrl.val);
        }
      }, true);

      $scope.$watch('ctrl.val', function(newValue, oldValue) {
        if (newValue) {
          doSelect(newValue);
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