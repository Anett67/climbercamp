import $ from 'jquery';

$(function(){

    $('.like-button').click(function(e){

            var link = $(this);
            var id = link.attr('id');
            var count = $('span#count' + id);
            var label = $('span.js-postLikes-label' + id);

            e.preventDefault();

            var url = $(this).attr('href');
                    
                if(label.hasClass('liked')){
                    label.removeClass('liked');
                    label.text('J\'aime');
                }else{
                    label.addClass('liked');
                    label.text('Je n\'aime plus');
                    
                }
            
            $.post(
                url,
                {},
                function(response){
                    count.html(response.likes);
                }

            );

        });

    // $('.comment-button').click(function(e){

    //     e.preventDefault();

    //     var url = $(this).attr('href');

    //     $.post(
    //         url,
    //         {},
    //         function(response){
    //             $('.comments-section').html(response);
    //         }
    //     )

    // });

    
    

});

    



