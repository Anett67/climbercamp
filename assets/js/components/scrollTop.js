import $ from 'jquery';

//icon to go back on the top of the page
var topWindow = $('#scrolltop-div');

//make the icon appear if we go down 500px from the top and makes it dissappear when we scroll up again
$(window).scroll(function() {

    if ($(this).scrollTop() > 500) {
        topWindow.fadeIn(500);
    } 
    else { 
        topWindow.fadeOut(500);
    }
});

//Animation to go back to the top of the page when clicking in the scrolltop icon
topWindow.click(function(){
    $('html, body').animate({scrollTop:0}, 'slow');
});