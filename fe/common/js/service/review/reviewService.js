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
                for(var i = 0; i < item.votes.length; i++) {

                    var voteValue = { "type" : "string" , "value" : item.votes[i]["vote_value"] };

                    ret.votes.push = {
                        id_vote_types: item.votes[i]["id_vote_type"],
                        vote_value: angular.toJson(voteValue)
                    };
                }

                if (item.id) {
                    ret.id = item.id
                }

                return ret;
            }
        }


    }]);
