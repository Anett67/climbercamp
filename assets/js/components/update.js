import $ from 'jquery';

//Load forms to update posts, events and responses with AJAX

$('body').on('click', '.update-link', function(event){
    event.preventDefault();

    //The container where we want to insert the form
    var postBlock = $(this).closest('.update-block');
    //AJAX endpoint url
    var url = $(this).attr('href');

    //show spinner
    postBlock.html('<div class="spinner-front"></div>');
    postBlock.addClass('green-spinner');
    
    $.post(
        url,
        {},
        function(response){
            //hide spinner
            postBlock.removeClass('green-spinner');
            //Load the template of the update form returned by the AJAX endpoint
            postBlock.html(response.response).slideDown();
        }
    )
    
});

//Load back the template of the post, event or response if user clicks on the cancel button
$('body').on('click', '.cancel', function(){

    //AJAX endpoint
    var url = $(this).data('url');

    //Checks if the object to update is a comment reply
    if ($(this).hasClass('reply-update-cancel')){
        var postBlock = $(this).parent().parent('.update-block');
    }else{
        var postBlock = $(this).parent('.update-block');
    }
    
    //show spinner
    postBlock.html('<div class="spinner-front"></div>');
    postBlock.addClass('green-spinner');
    
    $.post(
        url,
        {},
        function(response){
            //hide spinner
            postBlock.removeClass('green-spinner');
            //Load back the template of the post, event or response
            postBlock.html(response);
        }
    )  
});

