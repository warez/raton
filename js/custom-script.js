$=jQuery.noConflict();


function callPosts() {

    var postId = $("#ratonPostId").val();

    $.ajax({
        url: WP_API_Settings.root + '?rest_route=/raton/v1_0/item/' + postId,
        method: 'GET',
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nonce', WP_API_Settings.nonce);
        }
    }).done(function (response) {
        console.log(response);
    });
}