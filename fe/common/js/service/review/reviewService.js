angular.module("JRatonCommon").service("ReviewService", ['ReviewResource', '$filter',
    function (ReviewResource, $filter) {
        return {
            create: function (data) {
                return ReviewResource.create(data);
            },

            update: function (data) {
                return ReviewResource.update(data);
            },

            get: function (data) {
                return ReviewResource.get(data);
            },

            search: function (data) {
                return ReviewResource.search(data);
            },

            delete: function (data) {
                var data = {id: data.id};
                return ReviewResource.delete(data);
            },

            testEditReview: function (item) {
                if (!item.id || !item.review || !item.id_item || !item.votes)
                    return false;

                return true;
            },

            testCreateReview: function (item) {
                if (!item || !item.review || !item.id_item || !item.votes)
                    return false;

                return true;
            },

            prepareDBReview: function (item) {
                var ret = {};

                ret.review = item.review.trim();
                ret.id_item = item.id_item;
                ret.votes = [];

                angular.forEach(item.votes, function(value, key) {
                    var voteValue = { "type" : "star" , "value" : value["vote_value"] , "meta" : [] };
                    ret.votes.push( {
                        id_vote_types: key,
                        vote_value: voteValue
                    });
                });

                if (item.id) {
                    ret.id = item.id
                }

                return ret;
            }
        }


    }]);
