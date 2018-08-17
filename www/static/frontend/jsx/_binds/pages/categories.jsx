import isMedia from "../../utils/isMedia";
import documentReady from "../../utils/documentReady";

(function () {
    documentReady(() => {

        // высота меню
        const MENU_HEIGHT = 50;

        let buttons = $('.all-departments-menu-container').css('overflow', 'hidden');
        let sections = $('.sections');
        let container = buttons.parent().css('height', 'auto');
        let initialHeight = container.height();
        let visibleElement = buttons.find('.all-departments-menu');
        visibleElement.data('type', 'menu');
        let duration = 400;
        let scrollDuration = 800;


        function getSectionId() {
            let p = window.location.toString();
            p = p.match(new RegExp('#id[0-9]+'));
            return p ? p[0] : false;
        }

        function scrollToSection(sectionId) {
            $('html,body').stop().animate({scrollTop: $(sectionId).offset().top - MENU_HEIGHT}, scrollDuration);
        }

        container.css({
            'height': initialHeight + 'px',
            'overflow': 'hidden'
        });

        // Открыть пункт меню (закрыть меню)
        $('#content').on('click', '.item-title', function (event) {

            event.preventDefault();
            let oneButton = $(event.target).is('.item-title') ? $(event.target) : $(event.target).parents('.item-title');
            let idSection = oneButton.attr('href');
            let oneSection = sections.find(idSection);

            if (!isMedia('large')) {

                oneSection.css('display', 'block');
                let sectionHeight = oneSection.outerHeight(true);

                buttons.animate({
                    'height': 0
                }, duration);

                container.animate({
                    'height': sectionHeight + 'px'
                }, duration);

                visibleElement = oneSection;
                visibleElement.data('type', 'section');

                return false;

            } else {
                // Если десктопная версия - запомнить новое актуальное состояние
                visibleElement = oneSection;
                visibleElement.data('type', 'section');
                scrollToSection(this.hash);
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


        // После загрузки css открыть нужный пункт меню если передан его идентификатор
        $(document).on('app.start', function () {
            if (!getSectionId()) {
                return false;
            }

            let sectionId = getSectionId();
            // Клик на нужную ссылку записывает текущее значение в десктопной версии
            $('a.link-' + sectionId.replace('#', '')).trigger('click');


        });
    });
})();