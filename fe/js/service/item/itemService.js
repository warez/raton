angular.module("JRatonApp").service("ItemService", ['ItemResource',
    function (ItemResource) {
        return {
            getFromCategory: function (itemId, page, perPage) {
                return ItemResource.getFromCategory({from:itemId, page: page, per_page: perPage});
            },

            create: function (data) {
                return ItemResource.create(data);
            },

            update: function (data) {
                return ItemResource.update(data);
            },

            get: function (data) {
                return ItemResource.get(data);
            },

            delete: function (data) {
                var data = {id: data.id};
                return ItemResource.delete(data);
            },

            testEditItem: function(item) {
                if(!item || !item.title || !item.id || !item.id_category)
                    return false;

                return true;
            },

            testCreateItem: function(item) {
                if(!item || !item.title || !item.id_category)
                    return false;

                return true;
            },

            prepareDBItem: function(item) {
                var ret =  {};

                ret.title = item.title.trim();
                ret.description = item.description.trim();
                ret.id_category = item.id_category;
                ret.approved = item.approved;
                ret.request_approve = item.request_approve;

                if(item.id) {
                    ret.id = item.id
                }

                return ret;
            }
        }


    }]);
