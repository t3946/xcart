import isMedia from "../utils/isMedia";
import cssFileLoaded from "../utils/cssFileLoaded";

(()=>{

    // После загрузки css
    $(document).on('app.start', function () {

        var lastKnownScrollPosition = 0;
        var ticking = false;

        if (isMedia('large')) {

            let stickyContainer = $('.sticky-container');
            let sticky = stickyContainer.find('.sticky');

            let checkMenuPosition = (lastKnownScrollPosition) => {

                if (isMedia('large')) {
                    if (lastKnownScrollPosition >= stickyContainer.offset().top) {
                        sticky.addClass('menu-fixed');
                    } else {
                        sticky.removeClass('menu-fixed');
                    }
                }
            };

            let processScroll = _.throttle(() => {
                lastKnownScrollPosition = window.scrollY;
                if (!ticking) {
                    window.requestAnimationFrame(() => {
                        checkMenuPosition(lastKnownScrollPosition);
                        ticking = false;
                    });
                    ticking = true;
                }
            }, 50);

            let initStickyMenu = () => {

                let heightOfStickyBlock = sticky.innerHeight;
                if(heightOfStickyBlock <= 0){
                    return;
                }

                // stickyContainer.css({
                //     'display': 'block',
                //     'height': heightOfStickyBlock + 'px'
                // });

                window.addEventListener('scroll', processScroll, {'passive' : true});
            };


            cssFileLoaded('styles.css', initStickyMenu);
        }
    });
})();