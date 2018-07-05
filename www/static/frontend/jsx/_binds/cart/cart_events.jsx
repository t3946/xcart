import CountUp from 'countUp.js';
import storeCart from '../../stores/StoreCart';
import storeApp from '../../stores/StoreApp';
import CreateWaitButton from '../../components/AnimateWaitButton';

import { hideAll, action } from "../../redusers/appHeadReduser";
import { cartAdd } from "../../redusers/appCartRediser";
import sendAnalytics from "../../utils/sendAnalytics";

import {h, render} from 'preact';
import SelectNumberItems from "../../components/SelectNumberItems";

(()=>{
    let $minicart = $('.minicart');
    let minicartTimeout, timerIsStarted = false;

    let checkEnableMinicart = () => {
        // let state = storeApp.getState();
        let stateCart = storeCart.getState();

        if (stateCart.cart.quantity > 0) {
            $minicart.addClass('enabled');
        }
        else {
            hideAll();
            if(timerIsStarted){
                clearTimeout(minicartTimeout);
                timerIsStarted = false;
            }

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

        let number = product.querySelector('.add-product .number-button span');
        let val = parseInt(number.dataset.min, 10);

        number.innerHTML = val;
        product.dataset.quantity = val;

        // $(document).trigger('component.quantity.change', {
        //     target: product,
        //     val: val,
        //     product: product,
        // });
    };

    $(document)
        .on('click','.cart_add .add', (e) =>{
            e.preventDefault();
            let buttonAnimation = CreateWaitButton(e.target.closest('.wait-button'));
            buttonAnimation.start();

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
                } else {
                    mc_count.classList.remove('small');
                }

                mc_count.innerHTML = qNew;

                (new CountUp('desktop-cart-quantity', qPrev, qNew,0, 1, {useEasing: true})).start();
            }
        })
        .on('mouseenter', '.minicart.enabled', (e) => {

            e.preventDefault();
            e.stopPropagation();

            if(timerIsStarted){
                clearTimeout(minicartTimeout);
                timerIsStarted = false;
            }

            if (!$minicart.hasClass('active')) {

                $minicart.addClass('active');
                window.LazyLoad.update();
                action('cart');
            }

        })
        .on('mouseleave', '.minicart.enabled', (e) => {

            e.preventDefault();
            e.stopPropagation();

            if ($minicart.hasClass('active')) {

                 minicartTimeout = setTimeout(() => {

                    $minicart.removeClass('active');
                    hideAll();
                    timerIsStarted = false;

                }, 600);
                timerIsStarted = true;
            }
        })
        .on('click','.cart_add .number-button', (event) => {

            event.preventDefault();
            event.stopPropagation();

            let target = (event.target.tagName == 'SPAN') ? event.target : $(event.target).find('span').get(0);
            let selectQuantity = document.querySelector('.select-quantity');
            let product = event.target.closest('[data-product]');

            if (selectQuantity && product) {

                $(selectQuantity).mmodal({
                    'windowClass': 'quantitySelector',
                    'setWidth': false
                });

                let window = $('.mmodal-content .select-quantity').get(0);
                let number = parseInt(target.dataset.number, 10);
                let max = parseInt(target.dataset.max, 10);
                let min = parseInt(target.dataset.min, 10);
                let step = parseInt(target.dataset.step, 10);
                let quantity = product.dataset.quantity || min;
                let changeQuantity = e => {

                    let quantity = parseInt(e.detail.quantity, 10);
                    product.dataset.quantity = quantity;
                    target.innerHTML = quantity;
                    $(document).data('mmodal').close();
                    document.removeEventListener('component.select_number_items.change', changeQuantity);
                };

                document.addEventListener('component.select_number_items.change', changeQuantity);

                render(<SelectNumberItems number={number} quantity={quantity} max={max} min={min} step={step}/>,
                    window, window.firstChild);
            }

        });





})();