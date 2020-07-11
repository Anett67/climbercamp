import $ from 'jquery';

var burger = $('.burger');

$('body').on('click', '.burger',function(){
    $('.menu-block').toggleClass('menu-mobile');
    $('.menu-list').toggle();
    
});
