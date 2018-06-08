import CountUp from 'countUp.js';
import storeCart from '../../stores/StoreCart';
import storeApp from '../../stores/StoreApp';

import { hideAll, action } from "../../redusers/appHeadReduser";
import { cartAdd } from "../../redusers/appCartRediser";
import sendAnalytics from "../../utils/sendAnalytics";

(()=>{
    let $minicart = $('.minicart');

    let checkEnableMinicart = () => {
        // let state = storeApp.getState();
        let stateCart = storeCart.getState();

        if (stateCart.cart.quantity > 0) {
            $minicart.addClass('enabled');
        }
        else {
            hideAll();

            $minicart.removeClass('enabled');
            $minicart.removeClass('active');
        }
    };

    checkEnableMinicart();

    let unsubscribeCart = storeCart.subscribe(() => {
        checkEnableMinicart();

    });
    let unsubscribeApp = storeApp.subscribe(()=>{
        let state = storeApp.getState();

        if (state.frontend.header.active !== 'cart') {
            $minicart.removeClass('active');
        }
    });


    let productItemResetState = product => {
        console.log(product);
        let input = product.querySelector('.quantity-group input');
        let val = input.min;

        input.value = val;
        product.dataset.quantity = val;

        $(document).trigger('component.quantity.change', {
            target: product,
            val: val,
            product: product,
        });
    };

    $(document)
        .on('click','.cart_add .add', (e) =>{
            e.preventDefault();

            let product = e.target.closest('[data-product]');
            if ( product )
            {
                let data = [{
                    id: product.dataset.product,
                    quantity: product.dataset.quantity  || 1
                }];

                cartAdd(data, ()=>{ productItemResetState(product); });
                window.sendAnalytics.addToCart(product);
            }

        })
        .on('click', '.group_cart_add .button', (e) => {
            e.preventDefault();

            let products = e.target
                .closest('[data-product-group]')
                .find('[data-product]');

            if ( products.length ) {
                let data = [];

                for (let i = 0, len = products.length; i < len; i++) {
                    if (products[i].dataset.quantity) {
                        data.push({
                            id: products[i].dataset.product,
                            quantity: products[i].dataset.quantity,
                        });
                    }
                }

                cartAdd(data, ()=>{
                    for (let i = 0; i < products.length; ++i) {
                        productItemResetState(products[i]);
                    }
                });
            }
        })
        .on('update.cart.store', (e, data) => {
            let qNew = data.state.cart.quantity;
            let qPrev = data.prevState.cart.quantity;
            let mc_count = document.querySelector('.mc_count');

            if (mc_count) {
                if (qNew > 99) {
                    mc_count.classList.add('small');
                }
                else {
                    mc_count.classList.remove('small');
                }

                mc_count.innerHTML = qNew;

                (new CountUp('desktop-cart-quantity', qPrev, qNew,0, 1, {useEasing: true})).start();
            }
        })
        .on('click', '.minicart.enabled .cart_info', (e) => {
            e.preventDefault();

            if ($minicart.hasClass('active')) {
                $minicart.removeClass('active');
                hideAll()
            }
            else {
                $minicart.addClass('active');
                action('cart');
            }
        }).on('click','.cart_add .number-button', (e) => {
            e.preventDefault();
            let selectQuantity = document.querySelector('.select-quantity');
            if (selectQuantity) {
                $(selectQuantity).mmodal({
                    'width': 300,
                    'onSubmit': function () {}
                });
            }
        });





})();