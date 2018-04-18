import ajax from "../utils/ajax";

(()=>{
    let fncHideBlock = slide => {
        slide.classList.remove('loading');
        slide.closest('.slider-block').classList.add('hide');
    };

    let fncSlyAttach = (slide) => {
        window.addEventListener('resize', () => $(slide).sly('reload'));

        $(slide).sly({
            horizontal: 1,
            itemNav: 'basic',
            speed: 300,
            mouseDragging: 1,
            touchDragging: 1,
            releaseSwing: 1,
            dragHandle: 1,
            dynamicHandle: 1,
            clickBar: 1,
            scrollBar: $(slide).closest('.slider-block').find('.scrollbar'),
            scrollBy: 0,
            scrollTrap: true,
            // pagesBar: $wrap.find('.pages'),
            activatePageOn: 'click'
        })
            .css('overflow', 'visible');
    };

    document.addEventListener('sliders_show', ()=>{
        let sliders = document.querySelectorAll('.slider-block .slider-data:not(.loaded):not(.loading):not(.not-load)');

        if (sliders.length) {
            for (let i=0; i < sliders.length; i++) {
                let slide = sliders[i];
                slide.classList.add('loading');

                if (slide.dataset.url)
                {
                    ajax( slide.dataset.url )
                        .then(data => {
                            if (data) {
                                slide.innerHTML = data.html;
                                slide.classList.add('loaded');

                                fncSlyAttach(slide);
                            }
                            else {
                                fncHideBlock(slide);
                            }

                            slide.classList.remove('loading');
                        })
                        .catch(error => {
                            console.error(
                                error.message,
                                slide.dataset.url
                            );

                            fncHideBlock(slide);
                        })
                    ;

                }
            }
        }
    });

    let evnt = new CustomEvent('sliders_show');
    document.dispatchEvent(evnt);
})();