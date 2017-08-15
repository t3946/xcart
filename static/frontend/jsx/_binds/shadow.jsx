$(document).on('show:dm', ()=> {
    $('.shadow').addClass('active');
});

$(document).on('hide:dm', ()=> {
    $('.shadow').removeClass('active');
});

$('.shadow').on('click touchstart', ()=> {
    $(document).trigger('click:shadow');
});