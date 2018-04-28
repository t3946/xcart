import isMedia from "../../utils/isMedia";

// console.log('11');

$(function () {


    let buttons = $('.all-departments-menu');
    let sections = $('.sections');
    let container = buttons.parent().css('height','auto');
    let initialHeight = container.height();

    container.css({
        'height': initialHeight + 'px',
        'overflow': 'hidden'
    });

    $('#content').on('click', '.item-title', function (event) {

        // if (!isMedia('large') ) {

        event.preventDefault();

        let oneButton = $(event.target).is('.item-title') ? $(event.target) : $(event.target).parents('.item-title');
        let idSection = oneButton.attr('href');
        let oneSection = sections.find(idSection);

        oneSection.css('display', 'block');
        let sectionHeight = oneSection.height();

        buttons.slideUp(400);
        container.animate({
            'height': sectionHeight + 'px'
        }, 400);

        return false;
        // }

    });

    sections.on('click', '.departments-submenu-title', function (event) {

        // if (!isMedia('large') ) {
        event.preventDefault();

        let closeButton = $(event.target).is('.departments-submenu-title') ? $(event.target) : $(event.target).parents('.departments-submenu-title');
        let oneSection = closeButton.parents('section.departments-submenu-container');
        buttons.slideDown(400, function(){
            container.animate({
                'height': buttons.outerHeight() + 'px'
            }, 400, function () {
                oneSection.css('display', 'none');
            });
        });


        return false;

        // }
    });

     // window.addEventListener('resize', e =>  {
     //
     // })


    // document.querySelectorAll('#content').addEventListener('click', e => {
    //
    // });
    //
    // window.addEventListener('resize', e =>  {
    //
    // })
});