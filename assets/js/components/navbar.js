import $ from 'jquery';

$('body').on('click', '.burger',function(){
    $('.menu-block').toggleClass('menu-mobile');
    $('.menu-list').toggle();
    
});
