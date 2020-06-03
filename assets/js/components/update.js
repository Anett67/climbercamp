import $ from 'jquery';

$(function(){

    $('body').on('click', '.post-update-link', function(event){
        event.preventDefault();
        var postBlock = $(this).closest('.my-post-block');
        var url = $(this).attr('href');
    
        postBlock.html('<div class="spinner-front"></div>');
        postBlock.addClass('green-spinner');

        $.post(
            url,
            {},
            function(response){
                postBlock.removeClass('green-spinner');
                postBlock.html(response.response).slideDown();
            }
        )
        
    });

    $('body').on('click', '.cancel', function(){
        var url = $(this).data('url');
        var postBlock = $(this).parent('.my-post-block');
        
        postBlock.html('<div class="spinner-front"></div>');
        postBlock.addClass('green-spinner');

        $.post(
            url,
            {},
            function(response){
                postBlock.removeClass('green-spinner');
                postBlock.html(response);
            }
        )  
    });
});

