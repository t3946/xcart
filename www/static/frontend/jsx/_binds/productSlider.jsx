import {h, render} from 'preact';
import ProductImageSlider from "../components/ProductImageSlider";

(()=>{

    // let elements = document.querySelectorAll('.product__images-slider');
    //
    // if (elements.length) {
    //
    //     for (let i = 0, len = elements.length; len > i; i++) {
    //         let item = elements[i];
    //
    //         for (let i = 0; i < item.children.length; i++) {
    //             if (item.children[i].nodeName === 'DATALIST') {
    //
    //                 for (let i = 0; i < item.children.length; i++) {
    //                     if (item.children[i].nodeName === 'OPTION') {
    //
    //
    //
    //                     }
    //                 }
    //
    //             }
    //         }
    //     }
    // }


    $('.product__images-slider').each((i, item) => {
        let data = $(item).find('datalist');

        if (data.length) {

            let items = [];

            let options = data.find('option');

            if (options.length)
            {
                options.each((n, option) => {
                    let type = option.getAttribute('type').toLowerCase();

                    if (type === 'image') {
                        items.push({
                            type: type,
                            src: option.value,
                            id: option.dataset.id || null,
                            alt: option.dataset.alt || null,
                            title: option.dataset.title || null,
                            thumb: option.dataset.thumb || null,
                        })
                    }

                    if (type === 'video') {
                        items.push({
                            type: type,
                            href: option.value,
                            alt: option.dataset.alt || null,
                            title: option.dataset.title || null,
                            img: option.dataset.poster || null,
                            thumb: option.dataset.thumb || null,

                        });
                    }

                    if (type === 'html') {
                        items.push({
                            type: type,
                            html: option.innerHTML,
                            title: option.dataset.title || null,
                            thumb: option.dataset.thumb || null,
                        });
                    }
                });

                render(<ProductImageSlider items={items} />, item);
            }
        }
    });
})();