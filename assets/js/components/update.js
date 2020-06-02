import $ from 'jquery';

$(function(){

    var postUpdateLink = $('.post-update-link');

    
    $('body').on('click', '.post-update-link', function(event){
        event.preventDefault();
        var postBlock = $(this).closest('.my-post-block');
        var url = $(this).attr('href');

        $.post(
            url,
            {},
            function(response){
                postBlock.html(response.response);
                
            }
        )
        var submitButton = postBlock.children('.submit-button');
        
    });

    $('body').on('click', '.cancel', function(){
        var url = $(this).data('url');
        var postBlock = $(this).parent('.my-post-block');
        
        $.post(
            url,
            {},
            function(response){
                postBlock.html(response);
            }
        )
        
    });

    

});

