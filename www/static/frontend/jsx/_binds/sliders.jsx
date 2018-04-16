import ajax from "../utils/ajax";

(()=>{
    document.addEventListener('sliders_show', ()=>{
        let sliders = document.querySelectorAll('.slider-block .slider-data:not(.loaded):not(.loading)');

        if (sliders.length) {
            for (let i=0; i < sliders.length; i++) {
                let slider = sliders[i];
                slider.classList.add('loading');

                if (slider.dataset.url)
                {
                    ajax( slider.dataset.url, {},
                        (data) => {
                            console.log(data);
                        })
                        .then(data => {
                            slider.classList.remove('loading');
                            slider.classList.add('loaded');

                            console.log(data, 'd2');
                        });

                }
            }
        }
    });

    let evnt = new CustomEvent('sliders_show');
    document.dispatchEvent(evnt);
})();