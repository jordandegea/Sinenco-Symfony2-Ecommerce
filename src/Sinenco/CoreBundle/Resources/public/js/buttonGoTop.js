$(document).ready(function () {
    $('body').append('<a href="#top" class="top_link" title="Revenir en haut de page">Go Top</a>');
    $('.top_link').css({
        'color':'white',
        'text-align':'center',
        'width':'60px',
        'height':'60px',
        'padding': '10px',
        'position': 'fixed',
        'right': '20px',
        'bottom': '50px',
        'display': 'none',
        'background': '#64c4FF',
        '-moz-border-radius': '10px',
        '-webkit-border-radius': '10px',
        'border-radius': '10px',
        'opacity': '1',
        'z-index': '2000'
    });
});
$(window).scroll(function () {
    posScroll = $(document).scrollTop();
    if (posScroll >= 550)
        $('.top_link').fadeIn(600);
    else
        $('.top_link').fadeOut(600);
});