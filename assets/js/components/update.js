import $ from 'jquery';

$(function(){

    $('body').on('click', '.update-link', function(event){
        event.preventDefault();
        var postBlock = $(this).closest('.update-block');
        var url = $(this).attr('href');
    
        postBlock.html('<div class="spinner-front"></div>');
        postBlock.addClass('green-spinner');

        console.log(url);
        

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
        var postBlock = $(this).parent('.update-block');
        
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

