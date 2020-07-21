import $ from 'jquery';

$('body').on('click', '.like-button', function(e){

    e.preventDefault();
    
    var link = $(this);
    //Current posts id
    var id = link.attr('id');
    //The element containing the number of likes
    var count = $('span#count' + id);
    //The text of the like button
    var label = $('span.js-postLikes-label' + id);
    //AJAX endpoint url
    var url = $(this).attr('href');
    
    //Changing the buttons text
    if(label.hasClass('liked')){
        label.removeClass('liked');
        label.text('J\'aime');
    }else{
        label.addClass('liked');
        label.text('Je n\'aime plus');   
    }

    $.post(
        url,
        function(response){
            //Refresh the number of likes
            count.html(response.likes);
        }
    );
});

$('body').on('click', '.commentLike-button',function(e){

    e.preventDefault();

    //Like button
    var button = $(this);
    //Text of the button
    var label = button.children('span');
    //AJAX endpoint url
    var url = $(this).attr('href');
    //The element containing the number of comment likes
    var count = button.parent().prev().children('.like-count');

    //Changing the text of the button
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
            //Refresh the count of likes
            count.html(response.likes);
        }
    );
});

$('body').on('click', '.delete-post', function(e){

    if(!confirm('Confirmer la suppression?')){
        e.preventDefault();
    }

});
  

    



