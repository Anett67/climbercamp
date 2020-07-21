import $ from 'jquery';

//Shos the menu when the burger icon is clicked
$('body').on('click', '.burger',function(){
    $('.menu-block').toggleClass('menu-mobile');
    $('.menu-list').toggle();
    
});
