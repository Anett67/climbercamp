import $ from 'jquery';

var currentPage = 1;
var lastPage = $('.totalCount').val();

if(lastPage){
    $(window).scroll(function() {
        if($(window).scrollTop() + $(window).height() == $(document).height()) {
            currentPage++;
            
            if(currentPage <= lastPage){

                $('.container').append('<div class="row spinner-block justify-content-center justify-content-lg-end mt-3 mb-3"><div class="col-10 col-lg-9"><div class="spinner-front green-spinner"></div></div></div>');
            
                $.ajax({
                    method: "POST",
                    url: "/posts/" + currentPage,
                })
                .done(function(response) {
                    $('.spinner-block').addClass('spinner-invisible');
                    $('.container').append(response);
                });
            }
        }
    });
}