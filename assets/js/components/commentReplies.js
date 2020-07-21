import $ from 'jquery';


//Shows the template with the responses of a comment
$('body').on('click', '.reply-button',function(e){

    e.preventDefault();

    var button = $(this);
    var replyBlock = button.parent().parent().next();
    var url = button.attr('href');
    
    $.post(
        url,
        {},
        function(response){
            //The AJAX endpoint returns the template with the comment's responses
            replyBlock.html(response.response);

            //Hides the form for sending new replies
            $('.new-reply-form').hide();
            
            //When the button is clicked the form slides down
            $('.new-reply-button').click(function(e){
                e.preventDefault();
                $('.new-reply-form').slideToggle();
            });

            //The link to hide the responses of the comment
            $('.hide-replies-button').click(function(e){
                e.preventDefault();
                var hideLink = $(this);
                var block = hideLink.closest('.comment-replies').html('');
            });
        }
    );
});

//Submission of a new reply with AJAX
$('body').on('click', '.new-reply-submit-button' ,function(e){
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
            //The AJAX endpoint returns the template of the responses with the new response
            replyBlock.html(data.response);

            //The code below empties the textarea
            $('#post_comment_reply_body, #event_comment_reply_body').val('');

            //Update the number of responses
            $('.commentReplyCount').html(data.replies);

            //Correction of the text next to the number of responses
            if(data.replies == 1){
                $('.responses-label-js').html(' Réponse');
            }else{
                $('.responses-label-js').html(' Réponses'); 
            }
            
        }
    })  
});
