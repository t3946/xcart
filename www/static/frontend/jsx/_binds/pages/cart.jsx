'use strict';

import _ from 'lodash';

(()=>{
    let page_cart = document.querySelector('.cart-page');
    if (page_cart) {
        let updateCart = product => {
            let key = product.dataset.key, quantity = product.dataset.quantity || 1;

            console.log(key, quantity);

        };

        updateCart = _.throttle(updateCart, 500);

        $(document).on('component.quantity.change', (e, data) => {
            updateCart(data.product);
        });
    }
})();