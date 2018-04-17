import ajax from "../utils/ajax";

(()=>{
    let fncHideBlock = slide => {
        slide.classList.remove('loading');
        slide.closest('.slider-block').classList.add('hide');
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
                            slide.classList.remove('loading');
                            console.log(data);

                            if (data) {
                                slide.innerHTML = data.html;

                                slide.classList.add('loaded');
                            }
                            else {
                                fncHideBlock(slide);
                            }
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