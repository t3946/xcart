import sendAnalytics from "../../utils/sendAnalytics";
import documentReady from "../../utils/documentReady";

(() => {

    let page = document.querySelector('.product-page');
    if (page) {

        let questionsContainer = $('#questions');

        $(document).on('app.start', function () {
            window.sendAnalytics.productDetail(page);
        });

        documentReady(() => {
            $.ajax('/product/api/' + page.dataset.product + '/', {
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
            });

            $('#product_tabs').on('click', '#questions-label', () => {

                $.ajax('/product-question/', {
                    'data': {
                        'productId': questionsContainer.data('productid')
                    },
                    'success': (html) => {
                        if (html) {
                            questionsContainer.html(html);
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
                            console.log(messageInfo['messageText'],  messageInfo['messageType']);
                            window.addFlashMessage(messageInfo['messageText'], messageInfo['messageType']);
                        }
                    }
                });
            });
        });
    }
})();