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
            }
        }


    }]);
