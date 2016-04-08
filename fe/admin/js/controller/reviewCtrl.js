angular.module("JRatonApp").controller("ReviewController", ['$scope', '$location', 'LoaderService', 'WPPathService',
    'VoteTypeService', 'ReviewService', '$uibModal', '$sessionStorage', 'ModalService', 'itemViewOpt',

    function ($scope, $location, LoaderService, WPPathService, VoteTypeService, ReviewService, $uibModal,
              $sessionStorage, ModalService, itemViewOpt) {

        var ctrl = this;
        ctrl.mainCtrl = $scope.$parent.mainCtrl;

        ctrl.selectedItem = $sessionStorage["item"];

        ctrl.reviewsData = {};
        ctrl.voteTypes = [];

        ctrl.filter = {
            idItem: parseInt(ctrl.selectedItem.id,10),
            page: 1,
            per_page: 10
        };

        ctrl.load = function() {
            ctrl.loadVoteType();
        };

        ctrl.loadVoteType = function() {

            LoaderService.start();

            var filter = { categoryId: ctrl.selectedItem.id_category };
            VoteTypeService.search(filter).$promise.then(function(data) {

                LoaderService.stop();
                ctrl.voteTypes = angular.copy(data);

            }, function(error) {
                LoaderService.stop();
                //TODO
            });

        };

        ctrl.search = function(page) {

            LoaderService.start();

            if(page)
                ctrl.filter.page = page;

            ReviewService.search(ctrl.filter).$promise.then(function(data) {

                LoaderService.stop();

                ctrl.reviewsData = angular.copy(data);
                ctrl.filter.page = data.page;
                ctrl.per_page = data.per_page;

            }, function(error) {
                LoaderService.stop();
                //TODO
            });
        };

        ctrl.getVoteValue = function(review, voteType) {
            for(var i = 0; i < ctrl.reviewsData.votes.length; i++) {
                var vote = ctrl.reviewsData.votes[i];

                if(review.id != vote.review_id ||
                   voteType.id != vote.id_vote_types)
                    continue;

                return vote.vote_value;
            }

            return null;
        };

        ctrl.isValidSearch = function() {

            return true;
        };

        ctrl.createReview = function() {

            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: WPPathService.getPartialUrl() + "/createReviewTmpl.html",
                controller: "CreateReviewCtrl",
                size: 'sm',
                resolve: {
                    item: function() {
                        return ctrl.selectedItem;
                    },
                    voteTypes: function() {
                        return ctrl.voteTypes.items;
                    }
                }
            });

            modalInstance.result.then(function (review) {

                LoaderService.start();

                ReviewService.create(review).$promise.then(function (ret) {
                    LoaderService.stop();
                    ctrl.search(1);
                    //TODO

                }, function (error) {

                    //TODO
                    LoaderService.stop();

                });

            });

        };

        ctrl.onEdit = function(review) {

            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: WPPathService.getPartialUrl() + "/createReviewTmpl.html",
                controller: "EditReviewCtrl",
                size: 'sm',
                resolve: {
                    review: function() {
                        return review;
                    }
                }
            });

            modalInstance.result.then(function (newReview) {

                LoaderService.start();

                ReviewService.update(newReview).$promise.then(function (ret) {
                    angular.copy(ret, newReview);
                    LoaderService.stop();
                    //TODO

                }, function (error) {

                    //TODO
                    LoaderService.stop();

                });

            });

        };

        ctrl.onDelete = function(review) {

            var doDelete = function() {
                LoaderService.start();
                ReviewService.delete(review).$promise.then(function(data){

                    LoaderService.stop();
                    ctrl.search(1);
                    //TODO

                }, function(error) {
                    LoaderService.stop();
                    //TODO
                })
            };

            var modalOptions = {
                closeButtonText: 'Annulla',
                actionButtonText: "Cancella!",
                headerText: "Attenzione",
                bodyText: "Sei sicuro di voler cancellare la recensione?"
            };

            ModalService.showModal({}, modalOptions).then(function () {
                doDelete();
            });

        };

        ctrl.pageChanged = function() {
            ctrl.search();
        };

        ctrl.load();
    }

]).controller('EditReviewCtrl', function ($scope, $uibModalInstance, ReviewService, review) {

    $scope.mode = "EDIT";
    $scope.data = review;
    $scope.title = "Modifica recensione";

    $scope.ok = function () {

        if (!ReviewService.testEditReview($scope.data)) {
            //TODO
            return;
        }

        var itemDB = ReviewService.prepareDBItem($scope.data);
        $uibModalInstance.close(itemDB);
    };

    $scope.cancel = function () {
        $uibModalInstance.dismiss('cancel');
    };

}).controller('CreateReviewCtrl', function ($scope, $uibModalInstance, ReviewService, item, voteTypes) {

    $scope.data = {
        id_item: item.id,
        review: '',
        votes: {}
    };

    $scope.item = item;
    $scope.voteTypes = voteTypes;
    $scope.mode = "CREATE";
    $scope.title = "Crea recensione";

    $scope.voteLimits = [];

    for(var i = 0; i < $scope.voteTypes.length; i++) {
        $scope.voteLimits[ $scope.voteTypes[i]["id"] ] = parseInt($scope.voteTypes[i]["vote_limit"]);
    }

    $scope.ok = function () {

        if (!ReviewService.testCreateReview($scope.data)) {
            return;
        }

        var itemDB = ReviewService.prepareDBReview($scope.data);
        $uibModalInstance.close(itemDB);
    };

    $scope.cancel = function () {
        $uibModalInstance.dismiss('cancel');
    };

});
