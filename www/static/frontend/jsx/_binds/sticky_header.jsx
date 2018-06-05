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
        var stickyHeaderRemoved = false;

        let sticky = stickyContainer.find('#top-header');
        let topFixed = false;
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

            // Выход если разрешение не для мобильного устройства
            if (isMedia('large')) {
                if(!stickyHeaderRemoved){
                    stickyContainer.css({
                        'height': 'auto'
                    });
                    sticky.removeClass('header-fixed');
                    topFixed = false;

                    window.removeEventListener('scroll', processScroll, {'passive': true});
                    stickyHeaderRemoved = true;
                }
                return;
            }
            stickyHeaderRemoved = false;

            let heightOfStickyBlock = sticky.height();
            if (heightOfStickyBlock <= 0 || isMedia('large')) {
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
                if(!topFixed){
                    sticky.addClass('header-fixed');
                    topFixed = true;
                }

            } else {
                if(topFixed){
                    sticky.removeClass('header-fixed');
                    topFixed = false;
                }
            }
            prevPosition = lastKnownScrollPosition;
        }


        cssFileLoaded('styles.css', initStickyMenu);
        $(window).resize(initStickyMenuOnResize);

    });
})();