import isMedia from "../../utils/isMedia";
import documentReady from "../../utils/documentReady";

// console.log('11');

(function () {
    documentReady(() => {


        let buttons = $('.all-departments-menu-container').css('overflow', 'hidden');
        let sections = $('.sections');
        let container = buttons.parent().css('height', 'auto');
        let initialHeight = container.height();
        let visibleElement = buttons.find('.all-departments-menu');
        let duration = 400;
        let scrollDuration = 800;

        container.css({
            'height': initialHeight + 'px',
            'overflow': 'hidden'
        });

        // Открыть пункт меню (закрыть меню)
        $('#content').on('click', '.item-title', function (event) {

            console.log('click');

            if (!isMedia('large')) {

                console.log('!large');
                event.preventDefault();

                let oneButton = $(event.target).is('.item-title') ? $(event.target) : $(event.target).parents('.item-title');
                let idSection = oneButton.attr('href');
                let oneSection = sections.find(idSection);

                oneSection.css('display', 'block');
                let sectionHeight = oneSection.outerHeight(true);

                console.log(oneSection);
                console.log(sectionHeight);

                buttons.animate({
                    'height': 0
                }, duration);
                container.animate({
                    'height': sectionHeight + 'px'
                }, duration);

                visibleElement = oneSection;
                visibleElement.data('type', 'section');

                return false;
            }

        });

        // Закрыть пункт меню (открыть меню)
        sections.on('click', '.departments-submenu-title', function (event) {

            if (!isMedia('large')) {
                event.preventDefault();

                let closeButton = $(event.target).is('.departments-submenu-title') ? $(event.target) : $(event.target).parents('.departments-submenu-title');
                let oneSection = closeButton.parents('section.departments-submenu-container');
                let menuHeight = buttons.find('.all-departments-menu').outerHeight();
                buttons.animate({
                    'height': menuHeight + 'px'
                }, duration);
                container.animate({
                    'height': menuHeight + 'px'
                }, duration, function () {
                    oneSection.css('display', 'none');
                });

                visibleElement = buttons.find('.all-departments-menu');
                visibleElement.data('type', 'menu');

                return false;

            }
        });

        // Если изменяется ширина окна
        window.addEventListener('resize', e => {
            if (!isMedia('large')) {
                // Если мобильные разрешения
                let height = visibleElement.outerHeight();
                sections.find('section.departments-submenu-container').css('display', 'none');
                if (visibleElement.data('type') == 'menu') {
                    // Если активный элемент - меню
                    buttons.css('height', height + 'px');
                } else {
                    // Если раскрыт 1 пункт меню
                    visibleElement.css('display', 'block');
                    buttons.css('height', 0)
                }
                container.css('height', height + 'px');
            } else {
                // Если десктопные разрешения
                sections.find('section.departments-submenu-container').css('display', 'block');
                container.css('height', 'auto');
                buttons.css({'height': 'auto', 'display': 'block'});
            }
        });

        // Прокрутка до верха страницы
        console.info(document.querySelectorAll('#scrollToTop'));
        document.querySelectorAll('#scrollToTop').addEventListener('click', e => {
            e.preventDefault();
            $('html,body').stop().animate({ scrollTop: 0 }, scrollDuration);
        });

        $("body").on('click', '[href*="#"]', function(e){
            if (isMedia('large')) {
                $('html,body').stop().animate({ scrollTop: $(this.hash).offset().top }, scrollDuration);
                e.preventDefault();
            }
        });


        // document.querySelectorAll('#content').addEventListener('click', e => {
        //
        // });
        //
        // window.addEventListener('resize', e =>  {
        //
        // })
    });
})();