'use strict';

import _ from 'lodash';

(()=>{
    let page_cart = document.querySelector('.cart-page');
    if (page_cart) {
        let recalc = () => {
            let products = page_cart.querySelectorAll('[data-product]');
            if (products) {
                let subtotals = Object.create(null);
                subtotals.wh = Object.create(null);
                subtotals.cart = 0;

                for (let i = 0; products.length > i; ++i) {
                    let product = products[i];
                    subtotals.cart =+ product.dataset.subtotal;
                    subtotals.wh[product.dataset.wh] =+ product.dataset.subtotal;
                }

                let whs = page_cart.querySelectorAll('.warehouse_subtotal');

                for (let i = 0; whs.length > i; ++i) {
                    let wh = whs[i];
                    wh.querySelector('.subtotal').innerHTML = toLocaleCurrency(subtotals.wh[wh.dataset.wh]);
                }

                page_cart.querySelector('.cart_subtotal').innerHTML = toLocaleCurrency(subtotals.cart);
            }
        };

        let updateCart = product => {
            let key = product.dataset.key, quantity = product.dataset.quantity || 1;

            console.log(key, quantity, product.dataset);

            recalc();


        };

        updateCart = _.throttle(updateCart, 500);

        $(document).on('component.quantity.change', (e, data) => {
            updateCart(data.product);
        });
    }
})();