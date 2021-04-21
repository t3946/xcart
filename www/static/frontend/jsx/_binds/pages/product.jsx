import documentReady from "../../utils/documentReady";

import { render } from 'preact';
import Catalog    from '@/components/catalog/Catalog';

(() => {
    const elem = document.getElementsByClassName( 'groupped-products' )[0];

    if ( !elem ) {
        return;
    }

    const sortingOptions = JSON.parse( elem.dataset.sortingOptions );
    const hideSort = !!elem.dataset.hideSort;
    const pager = JSON.parse( elem.dataset.pager );

    render(
        <Catalog
            sortingOptions={ sortingOptions }
            sortKey={ elem.dataset.currentSortingKey }
            hideSort={ hideSort }
            pager={ pager }
            catalogUrl={ '/api'+ elem.dataset.catalogUrl }
            checkoutUrl={ elem.dataset.checkoutUrl }
        />,
        elem,
    );

    let page = document.querySelector('.product-page');
    if (page) {

        let prices_table = page.querySelector('.table__prices--down');
        if (prices_table) {
            let prices_row = prices_table.querySelectorAll('.price-row');

            if (prices_row) {
                let timers = {};
                const $prices_table = $(page);
                const listPrice = parseFloat( $prices_table.find( '.column-price .product-quantity-old-price .price' ).text() );
                const $oldTotalPrice = $prices_table.find( '.column-extended .product-quantity-old-price .price' );

                $(document).on('component.quantity.change', (e, data) => {
                    //update old total price
                    $oldTotalPrice.text( ( listPrice * data.val ).toFixed( 2 ) );

                    if (data.product && data.product.dataset.product === page.dataset.product) {
                        let allHide = true;
                        let cnt = 0;

                        prices_row.forEach(price => {
                            let hide = (price.dataset.quantity <= data.val) || (cnt >= page.dataset.rows);
                            let key = 'price_' + price.dataset.quantity;

                            price.classList.toggle('hidden', hide);
                            price.classList.toggle('af-anim', hide);

                            if (!hide) {
                                allHide = false;
                                cnt++;
                            }
                        });

                        prices_table.classList.toggle('hidden', allHide);
                    }
                });
            }
        }

        let questionsContainer = $('#questions');

        $(document).on('app.start', function () {
            window.sendAnalytics.productDetail(page);
        });

        documentReady(() => {
            /*$.ajax('/product/api/' + page.dataset.product + '/', {
                'success': (data) => {
                    if (data.shipping && data.shipping.free_shipping) {
                        let notification_info = document.querySelectorAll('.notifications-info > .column');
                        if (notification_info.length) {
                            for (let i = 0; i < notification_info.length; i++) {
                                notification_info[i].innerHTML += data.shipping.free_shipping;
                            }
                        }

                    }
                }
            });*/

            function startTimer() {
                let timer_block = document.querySelector('.discount_block');
                if (timer_block) {
                    let display = timer_block.querySelector('.discount__counter');
                    let display_hours = display.querySelector('.hours');
                    let display_minutes = display.querySelector('.minutes');
                    let display_seconds = display.querySelector('.seconds');
                    let duration = timer_block.dataset.timer - 1;
                    let minutes_init = timer_block.dataset.minutes;
                    let timer = duration;
                    if (minutes_init > 0) {

                        setInterval(function () {

                            let hours = parseInt(timer / 3600, 10)
                            let minutes = parseInt((timer / 60) % 60, 10)
                            let seconds = parseInt(timer % 60, 10);

                            hours = hours < 10 ? "0" + hours : hours;
                            minutes = minutes < 10 ? "0" + minutes : minutes;
                            seconds = seconds < 10 ? "0" + seconds : seconds;

                            display_hours.textContent = hours;
                            display_minutes.textContent = minutes;
                            display_seconds.textContent = seconds;
                            if (timer >= 0) {
                                timer_block.style.display = 'block';
                            }
                            if (--timer < 0) {
                                timer_block.style.display = 'none';
                                document.querySelector('.price__quantity .price-row-width').style.display = 'none';
                                // timer = minutes_init * 60 - 1;
                            }
                        }, 1000);
                    }
                }
            }

            //startTimer();

            $('#product_tabs').on('click', '#questions-label', () => {

                $.ajax('/product-question/', {
                    'data': {
                        'productId': questionsContainer.data('productid')
                    },
                    'success': (html) => {
                        if (html) {
                            questionsContainer.html(html);
                            let formConstructedEvent = new CustomEvent('form.constructed', { detail: {} });
                            document.dispatchEvent(formConstructedEvent);
                        }
                    }
                });

            });

            $('#questions').on('submit', 'form', (event) => {
                event.preventDefault();

                $.ajax('/product-question/', {
                    'method': 'POST',
                    'data': $(event.target).serialize(),
                    'success': (html) => {
                        if (html) {
                            questionsContainer.html(html);
                            let messageInfo = questionsContainer.find('form').get(0).dataset;

                            if (!('messageText' in messageInfo)) {
                                return;
                            }
                            let text = questionsContainer.find('.' + messageInfo['messageText']).html();
                            window.addFlashMessage(text, messageInfo['messageType'], true);
                        }
                    }
                });
            });

        });
    }
})();