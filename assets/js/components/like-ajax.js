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

    $('.commentLike-button').click(function(e){

        e.preventDefault();

        var button = $(this);
        var label = button.children('span');
        var url = $(this).attr('href');
        var count = button.parent().prev().children('.like-count');

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

    $('.reply-button').click(function(e){

        e.preventDefault();

        var button = $(this);
        var replyBlock = button.parent().parent().next();
        var url = button.attr('href');
        
        $.post(
            url,
            {},
            function(response){
                replyBlock.html(response.response);

                $('.new-reply-form').hide();
                
                $('.new-reply-button').click(function(e){
                    e.preventDefault();
                    $('.new-reply-form').slideToggle();
                });

                $('.hide-replies-button').click(function(e){
                    e.preventDefault();
                    var hideLink = $(this);
                    var block = hideLink.closest('.comment-replies').html('');
                });

                $('.new-reply-submit-button').click(function(e){
                    e.preventDefault();

                    var button = $(this);
                    var url = button.closest('form').attr('action');
                    var form = button.closest('form');
                    var replyBlock = button.closest('.comment-replies');
                    var form_data = form.serialize();

                    $.ajax({
                        url: url,
                        type: 'POST',
                        dataType: 'json',
                        data: form_data,
                        success: function(data){
                            replyBlock.html(data.response);
                        }
                    })
                    
                });
            }

        );

    });

    


    
    
});

    


