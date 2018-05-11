import isMedia from "../utils/isMedia";
import cssFileLoaded from "../utils/cssFileLoaded";

(() => {


    // После загрузки css
    $(document).on('app.start', function () {

        var stickyContainer = $('.sticky-menu-container');

        // выход, если нет прилипающего меню
        if(stickyContainer.length <= 0){
            return;
        }

        var lastKnownScrollPosition = 0;
        var ticking = false;
        var containerHeightRemoved = false;

        let sticky = stickyContainer.find('.sticky');

        let processScroll = _.throttle(function () {

            lastKnownScrollPosition = window.scrollY;
            if (!ticking) {
                window.requestAnimationFrame(function () {
                    checkMenuPosition(lastKnownScrollPosition);
                    ticking = false;
                });
                ticking = true;
            }
        }, 50);

        let initStickyMenu = function () {

            // Выход если разрешение для мобильного устройства
            if (!isMedia('large')) {
                if(!containerHeightRemoved){
                    stickyContainer.css({
                        'height': 'auto'
                    });
                    containerHeightRemoved = true;
                }
                return;
            }
            containerHeightRemoved = false;

            let heightOfStickyBlock = sticky.height();
            if (heightOfStickyBlock <= 0) {
                return;
            }

            stickyContainer.css({
                'display': 'block',
                'height': heightOfStickyBlock + 'px'
            });

            window.addEventListener('scroll', processScroll, {'passive': true});
        };

        let initStickyMenuOnResize = _.throttle(initStickyMenu, 50);

        function checkMenuPosition(lastKnownScrollPosition) {

            if (lastKnownScrollPosition >= stickyContainer.offset().top) {
                sticky.addClass('menu-fixed');
            } else {
                sticky.removeClass('menu-fixed');
            }
        }


        cssFileLoaded('styles.css', initStickyMenu);
        $(window).resize(initStickyMenuOnResize);

    });
})();