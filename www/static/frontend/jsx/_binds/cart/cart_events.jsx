import CountUp from 'countUp.js';
import storeCart from '../../stores/StoreCart';
import storeApp from '../../stores/StoreApp';
import CreateWaitButton from '../../components/AnimateWaitButton';

import { hideAll, action } from "../../redusers/appHeadReduser";
import { cartAdd } from "../../redusers/appCartRediser";
import sendAnalytics from "../../utils/sendAnalytics";

import {h, render} from 'preact';
import SelectNumberItems from "../../components/SelectNumberItems";

(()=> {
    let $minicart = $('.minicart');
    let minicartTimeout, timerIsStarted = false;

    // инициализировать корзину в верхней части окна
    let checkEnableMinicart = () => {
        // let state = storeApp.getState();
        let stateCart = storeCart.getState();

        if (stateCart.cart.quantity > 0) {
            $minicart.addClass('enabled');
        }
        else {
            hideAll();
            if (timerIsStarted) {
                clearTimeout(minicartTimeout);
                timerIsStarted = false;
            }

            $minicart.removeClass('enabled');
            $minicart.removeClass('active');
        }
    };

    checkEnableMinicart();

    // переинициализировать корзину в верхней части окна при изменении глобального состояния
    let unsubscribeCart = storeCart.subscribe(() => {
        checkEnableMinicart();

    });

    let unsubscribeApp = storeApp.subscribe(() => {
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

    // function getOptionNameFromString(nameString){
    //
    //     let nameStringParts = nameString.split('[');
    //     let lastPart = nameStringParts.length - 1;
    //     let lastPartString = nameStringParts[lastPart];
    //     return lastPartString.substring(0, lastPartString.length - 1);
    // }

    $(document)
        // добавить товар в корзину
        .on('click', '.cart_add .add', (e) => {
            e.preventDefault();

            let buttonAnimation = CreateWaitButton(e.target.closest('.wait-button'));
            let product = e.target.closest('[data-product]');

            if ( product )
            {

                let infoFormId = e.target.closest('.cart_add').getAttribute('data-form-id');
                let form = document.getElementById(infoFormId);

                if(typeof document.formValidators !== 'undefined'
                    && document.formValidators[infoFormId] !== 'undefined'){

                    let formValidate = document.formValidators[infoFormId];
                    formValidate.checkAllForm();

                   // console.log(formValidate.hasErrors);
                    if(formValidate.hasErrors) {
                        return false;
                    }
                }

                let opt = [];

                // $('select.product-options', product).each(function(){
                //     opt.push({'o_id': this.dataset.id, 'ov_id': $(this).find('option:selected').val()});
                // });
                let values = $(form).serializeArray();
                for (let oneValue of values) {
                    //let name = getOptionNameFromString(oneValue.name);
                    let valueParts = oneValue.value.split('_');
                    let identifiersParts = valueParts[0].split('-');
                    //console.log(identifiersParts);
                    opt.push({'optionId': identifiersParts[0], 'variantId': identifiersParts[1]});
                }

                let data = [{
                    id: product.dataset.product,
                    quantity: product.dataset.quantity  || 1,
                    options: opt
                }];

                buttonAnimation.start();
                cartAdd(data, ()=>{ productItemResetState(product); });
                window.sendAnalytics.addToCart(product);
            }

        })
        .on('click', '.group_cart_add .button', (e) => {
            e.preventDefault();

            let products = e.target
                .closest('[data-product-group]')
                .find('[data-product]');

            if (products.length) {
                let data = [];

                for (let i = 0, len = products.length; i < len; i++) {
                    if (products[i].dataset.quantity) {
                        data.push({
                            id: products[i].dataset.product,
                            quantity: products[i].dataset.quantity,
                        });
                    }
                }

                cartAdd(data, () => {
                    for (let i = 0; i < products.length; ++i) {
                        productItemResetState(products[i]);
                    }
                });
            }
        })
        // Изменение колличества товара в корзине в верхней части окна
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

                (new CountUp('desktop-cart-quantity', qPrev, qNew, 0, 1, {useEasing: true})).start();
            }
        })
        // Раскрыть корзину в верхней части окна
        .on('mouseenter', '.minicart.enabled', (e) => {

            e.preventDefault();
            e.stopPropagation();

            if (timerIsStarted) {
                clearTimeout(minicartTimeout);
                timerIsStarted = false;
            }

            if (!$minicart.hasClass('active')) {

                $minicart.addClass('active');
                window.LazyLoad.update();
                action('cart');
            }

        })
        // Закрыть корзину в верхней части окна
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
        // Выбор количества товара
        .on('click', '.cart_add .number-button', (event) => {

            event.preventDefault();
            event.stopPropagation();

            let target = (event.target.tagName == 'SPAN') ? event.target : $(event.target).find('span').get(0);
            let selectQuantity = document.querySelector('.select-quantity');
            let product = event.target.closest('[data-product]');

            if (selectQuantity && product) {

                // Открытие всплывающего окна
                $(selectQuantity).mmodal({
                    'windowClass': 'quantitySelector',
                    'setWidth': false
                });

                // Всплывающее окно
                let window = $('.mmodal-content .select-quantity').get(0);
                let number = parseInt(target.dataset.number, 10);
                let max = parseInt(target.dataset.max, 10);
                let min = parseInt(target.dataset.min, 10);
                let step = parseInt(target.dataset.step, 10);
                let quantity = product.dataset.quantity || min;

                // Во всплывающем окне изменилось колличество товара
                let changeQuantity = e => {

                    let quantity = parseInt(e.detail.quantity, 10);
                    product.dataset.quantity = quantity;
                    target.innerHTML = quantity;
                    // Закрыть окно
                    $(document).data('mmodal').close();
                    document.removeEventListener('component.select_number_items.change', changeQuantity);
                };

                // Во всплывающем окне изменилось колличество товара
                document.addEventListener('component.select_number_items.change', changeQuantity);

                // Создание контента всплывающего окна
                render(<SelectNumberItems number={number} quantity={quantity} max={max} min={min} step={step}/>,
                    window, window.firstChild);
            }


        })

        .on('click', '.notify-me', (event) => {

            event.preventDefault();
            event.stopPropagation();

            let notify_win = document.getElementById('notify_get');

            if (notify_win === null) {

                let notifyContainer = document.querySelector('.notify_stock');

                notify_win = document.createElement('div');
                let notify_Wrapper = document.createElement('div');

                notify_win.setAttribute('id', 'notify_get');
                notify_Wrapper.style.position = 'absolute';
                notify_Wrapper.style.left = '-9999px';
                notify_Wrapper.style.right = '-9999px';
                notify_Wrapper.style.display = 'none';

                notify_Wrapper.appendChild(notify_win);
                notifyContainer.appendChild(notify_Wrapper);
            }

            let product_id = event.target.closest('.product-page').dataset.product;

            $.ajax({url: '/notify/get/', method: 'GET', data:{product_id: product_id}}).done(function(html) {

                let submitForm = null;
                $(notify_win).mmodal({
                    'windowClass': 'notifySelector',
                    'setWidth': false,
                    'onBeforeOpen': function (container) {
                        this.setContent(html);
                        submitForm = container.getElementsByTagName('form')[0];
                    },
                    'onAfterOpen': function() {
                        let evnt = new CustomEvent('sliders_show');
                        document.dispatchEvent(evnt);
                    },
                    'onSubmit': function () {
                        let $self = this;
                        let id = submitForm.getAttribute('id');

                        if(typeof document.formValidators !== 'undefined'
                            && typeof document.formValidators[id] !== 'undefined'){

                            let formValidator = document.formValidators[id];
                            formValidator.checkAllForm();

                            if(formValidator.hasErrors){
                                return false;
                            }
                        }

                        $.ajax({
                            url: '/notify/post/',
                            method: 'POST',
                            data: $('.mmodal_notify_stock form').serialize()

                        }).done(function(result) {
                            Object.keys(result).forEach(function(key, id){
                                window.window.addFlashMessage(result[key], key, false, 5);
                            });
                            $self.close();
                        });
                    },
                });

            });

        });


})();