import isMedia from "../utils/isMedia";
import cssFileLoaded from "../utils/cssFileLoaded";

(() => {
    // После загрузки css
    $(document).on('app.start', function () {

        var stickyContainer = $('#top-header-content');

        // выход, если нет прилипающей шапки
        if(stickyContainer.length <= 0){
            return;
        }

        var lastKnownScrollPosition = 0;
        var ticking = false;
        var containerHeightRemoved = false;

        let sticky = stickyContainer.find('#top-header');
        let prevPosition;

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
            if (isMedia('large') && isMedia('medium')) {
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

            if (lastKnownScrollPosition >= stickyContainer.offset().top && lastKnownScrollPosition < prevPosition) {
                sticky.addClass('header-fixed');
            } else {
                sticky.removeClass('header-fixed');
            }
            prevPosition = lastKnownScrollPosition;
        }


        cssFileLoaded('styles.css', initStickyMenu);
        $(window).resize(initStickyMenuOnResize);

    });
})();