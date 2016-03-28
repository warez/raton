angular.module("JRatonCommon").service("CategoryUtils", [
    'CategoryService', function (CategoryService) {

        var node = function(categoryObj) {
            this.id = categoryObj.id;
            this.title = categoryObj.title;
            this.description = categoryObj.description;
            this.children = [];
        };

        node.prototype.build = function (children) {
            if (!children)
                return this;

            for (var i = 0; i < children.length; i++) {
                var nodeI = new node(children[i].cat);
                nodeI.parent = this;
                this.children.push(nodeI);

                nodeI.build(children[i].subCats);
            }

            return this;
        };

    return {



        buildTree: function(data) {

            var root = null;
            if(data.cat)
                root = new node(data.cat);

            return root.build(data.subCats);
        },

        levelTree: function(data) {

            var ret = [];

            var manageSubCat = function(obj) {
                if(obj.cat)
                    ret.push(obj.cat);

                if(obj.subCats != null)
                    for(var i = 0; i < obj.subCats.length; i++)
                        manageSubCat(obj.subCats[i]);
            };

            manageSubCat(data);
            return ret;
        },

        onDelete: function (data) {
            if (!data.parent) {
                alert("Root!!!");
                return;
            }

            console.log("on-delete-all: delete node " + data.name + " from: " + parent.name);

            var index = data.parent.children.indexOf(data);
            data.parent.children.splice(index, 1);
        },

        onMove: function (data, op) {

            if (op === "up") {

                var brother = data.parent.children;
                var index = brother.indexOf(data);
                brother.splice(index, 1);
                brother.splice(index - 1 < 0 ? 0 : index - 1, 0, data);

            } else if (op === "down") {

                var brother = data.parent.children;
                var index = brother.indexOf(data);
                brother.splice(index, 1);
                brother.splice(index + 1 > brother.length ? index : index + 1, 0, data);

            }

        },

        copyCategory: function(source) {
            var dest = {};
            dest.id = source.id;
            dest.title = source.title;
            dest.description = source.description;
            return dest;
        },

        onEdit: function (data, ret) {
            data.id = ret.id;
            data.title = ret.title;
            data.description = ret.description;
        },

        onDeleteChildren: function (data) {
            console.log("on-delete: delete nodes from: " + data.name);
            data.children = [];
        },

        onAdd: function (data, newNode) {

            if (!newNode || !newNode.id )
                return;

            console.log("on-add: add node to: " + data.name);
            if (!data.children) {
                data.children = [];
            }

            newNode.parent = data;
            data.children.splice(0,0,newNode);
        }
    }

}]);