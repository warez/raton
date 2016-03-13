angular.module("JRatonApp").service("ItemService", ['ItemResource','$filter',
    function (ItemResource, $filter) {
        return {
            create: function (data) {
                return ItemResource.create(data);
            },

            update: function (data) {
                return ItemResource.update(data);
            },

            get: function (data) {
                return ItemResource.get(data);
            },

            search: function (data) {

                var dataCopy = angular.copy(data);
                if(dataCopy.creationTime != null) {
                    dataCopy.creationTime = $filter('fromDate')(dataCopy.creationTime);
                }
                if(dataCopy.updateTime != null) {
                    dataCopy.updateTime = $filter('fromDate')(dataCopy.updateTime);
                }

                return ItemResource.search(dataCopy);
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
