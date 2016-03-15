angular.module("JRatonApp").service("VoteTypeService", ['VoteTypeResource', '$filter',
    function (VoteTypeResource, $filter) {
        return {
            create: function (data) {
                return VoteTypeResource.create(data);
            },

            update: function (data) {
                return VoteTypeResource.update(data);
            },

            get: function (data) {
                return VoteTypeResource.get(data);
            },

            search: function (data) {
                return VoteTypeResource.search(data);
            },

            delete: function (data) {
                var data = {id: data.id};
                return VoteTypeResource.delete(data);
            },

            testEditItem: function (item) {
                if (!item ||
                    !item.id ||
                    !item.title ||
                    !item.id_category ||
                    !item.vote_limit ||
                    !angular.isNumber(item.vote_limit)
                )
                    return false;

                return true;
            },

            testCreateItem: function (item) {
                if (!item ||
                    !item.title ||
                    !item.id_category ||
                    !item.vote_limit ||
                    !angular.isNumber(item.vote_limit)
                )
                    return false;

                return true;
            },

            prepareDBItem: function (item) {
                var ret = {};

                ret.title = item.title.trim();
                ret.description = item.description.trim();
                ret.id_category = item.id_category;
                ret.vote_limit = item.vote_limit;
                ret.position = item.position ? parseInt(item.position) : 1;

                if (item.id) {
                    ret.id = item.id
                }

                return ret;
            }
        }


    }]);
