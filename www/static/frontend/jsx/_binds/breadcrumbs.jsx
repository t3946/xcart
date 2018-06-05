import isMedia from "../utils/isMedia";
import cssFileLoaded from "../utils/cssFileLoaded";

(() => {
    let breadcrumbs = document.querySelector('.breadcrumbs-container');
    if (breadcrumbs) {

        console.info('breadcrumbs');


        //console.info($(breadcrumbs).sly();
        // $('.frame .slidee').css({
        //     'display' : 'block',
        //     'width': '1000px'
        // });
        // let sly = new Sly('.frame', {
        //     horizontal: 1,
        //     itemNav: 'basic',
        //     speed: 300,
        //     mouseDragging: 1,
        //     touchDragging: 1,
        //     releaseSwing: 1,
        //     dragHandle: 1,
        //      dynamicHandle: 1,
        //      clickBar: 1,
        //      //scrollBar: $(slide).closest('.slider-block').find('.scrollbar'),
        //      scrollBy: 0,
        //      scrollTrap: true,
        // });

       // let sly = new Sly('.frame');
        //console.info(sly);
       // sly.init();



        // После загрузки css
        $(document).on('app.start', function () {

            function initBreadcrumbs() {


                setTimeout(function(){
                    $(breadcrumbs).sly({
                        horizontal: 1,
                        itemNav: 'basic',
                        smart: 1,
                        speed: 300,
                        mouseDragging: 1,
                        touchDragging: 1,
                        releaseSwing: 1,
                        dragHandle: 1,
                        dynamicHandle: 1,
                        clickBar: 1,
                        scrollBar: $('.scrollbar'),
                        scrollBy: 0,
                        scrollTrap: true,
                        // pagesBar: $wrap.find('.pages'),
                        activatePageOn: 'click'
                    });
                }, 3000);
            }

            cssFileLoaded('styles.css', initBreadcrumbs);
            //$(window).resize(initBreadcrumbsOnResize);

        });

    }
})();

