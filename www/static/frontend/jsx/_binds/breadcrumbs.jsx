import cssFileLoaded from "../utils/cssFileLoaded";

(() => {
    let breadcrumbs = document.querySelector('.breadcrumbs-container');
    if (breadcrumbs) {

        let sly;
        let options = {
            horizontal: 1,
            itemNav: 'basic',
            speed: 300,
            mouseDragging: 1,
            touchDragging: 1,
            releaseSwing: 1,
            dragHandle: 1,
            dynamicHandle: 1,
            scrollBy: 0,
            scrollTrap: true,
            activatePageOn: 'click'
        };

        // После загрузки css
        $(document).on('app.start', function () {

            function fixWidth(){
                $(breadcrumbs).find('li').each(function(){
                    let width = Math.ceil($(this).outerWidth());
                    $(this).css('width', width + 'px');
                });
            }

            function initBreadcrumbs() {
                fixWidth();
                sly = new Sly(breadcrumbs, options).init();
                sly.toEnd();
            }

            function reloadBreadcrumbs(){
                fixWidth();
                sly.reload();
                sly.toEnd();
            }


            initBreadcrumbs();
            $(window).resize(reloadBreadcrumbs);

        });

    }
})();

