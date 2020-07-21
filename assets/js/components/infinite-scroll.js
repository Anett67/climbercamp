import $ from 'jquery';
//The current page is always 1 by default
var currentPage = 1;
//Number of the last page on posts page
var lastPage = $('.posts-count').val();
//Number of the last pag on users page
var usersLastPage = $('.users-count').val();

//Checking if we are on posts page
if(lastPage){
    $(window).scroll(function() {
        if($(window).scrollTop() + $(window).innerHeight() >= $('body').height() - 100) {

            //Incrementing the current page is necessary for pagination
            currentPage++;
            
            //The AJAX request is sent only if we are not on the last page
            if(currentPage <= lastPage){

                //Show the spinner until the answer of the request arrives
                $('.container').append('<div class="row spinner-block justify-content-center justify-content-lg-end mt-3 mb-3"><div class="col-10 col-lg-9"><div class="spinner-front green-spinner"></div></div></div>');
                
                $.ajax({
                    method: "POST",
                    url: "/posts/" + currentPage,
                })
                .done(function(response) {
                    //Hide spinner
                    $('.spinner-block').addClass('spinner-invisible');
                    //Add more posts to the page
                    $('.container').append(response);
                });
            }
        }
    });
}

//Checking if we are on users page
if(usersLastPage){
    $(window).scroll(function() {
        if($(window).scrollTop() + $(window).innerHeight() >= $('body').height() - 100) {
            
            //Incrementing the current page is necessary for pagination
            currentPage++;
            
            //The AJAX request is sent only if we are not on the last page
            if(currentPage <= usersLastPage){
                
                //Show the spinner until the answer of the request arrives
                $('.container').append('<div class="row spinner-block justify-content-center justify-content-lg-end mt-3 mb-3"><div class="col-10 col-lg-9"><div class="spinner-front green-spinner"></div></div></div>');
                
                $.ajax({
                    method: "POST",
                    url: "/users/" + currentPage,
                })
                .done(function(response) {
                    //Hide spinner
                    $('.spinner-block').addClass('spinner-invisible');
                    //Add more users to the page
                    $('.card-container').append(response);
                });
            }
        }
    });
}