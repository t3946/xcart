import isMedia from "../utils/isMedia";

(() => {

    // После загрузки css
    $(document).on('app.start', function () {

        var lastKnownScrollPosition = 0;
        var ticking = false;


        let stickyContainer = $('.sticky-menu-container');
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

            if (!isMedia('large')) {
                return;
            }

            if (typeof stickyContainer === 'undefined') {
                stickyContainer = $('.sticky-menu-container');
                sticky = stickyContainer.find('.sticky');
            }

            let heightOfStickyBlock = sticky.height();
            if (heightOfStickyBlock <= 0) {
                return;
            }
            //console.log(heightOfStickyBlock);
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


        initStickyMenu();
        $(window).resize(initStickyMenuOnResize);

    });
})();