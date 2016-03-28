angular.module("JRatonCommon").service("CategoryService", ['CategoryResource',
    function (CategoryResource) {
        return {
            getCategoryTree: function(data) {
                return CategoryResource.getCategoryTree(data);
            },

            create: function(data) {
                return CategoryResource.create(data);
            },

            update: function(data) {
                return CategoryResource.update(data);
            },

            delete: function(data) {
                var data = {id: data.id};
                return CategoryResource.delete(data);
            },

            testDeleteCategory: function(cat) {
                return (cat.children && cat.children.length > 0);
            },

            testEditCategory: function(cat) {

                if(!cat || !cat.title || !cat.id)
                    return false;

                return true;

            },

            testNewCategory: function(cat) {

                if(!cat || !cat.title)
                    return false;

                return true;

            },

            getParentChain: function(data) {
                var ret = [];
                ret.push(data.title);

                while(data.parent != null) {

                    if(data.parent.id != 'ROOT')
                        ret.splice(0,0, data.parent.title);

                    data = data.parent;
                }

                return ret;
            },

            prepareDBCategory: function(cat, parent) {

                var ret =  {};

                ret.title = cat.title.trim();
                ret.description = cat.description.trim();

                if(cat.id) {
                    ret.id = cat.id
                }

                if(parent && parent.id != "ROOT") {
                    ret.id_parent_category = parseInt(parent.id, 10);
                }else {
                    ret.id_parent_category = -1;
                }

                return ret;

            }
        }
    }
]);