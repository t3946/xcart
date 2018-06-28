import sendAnalytics from "../../utils/sendAnalytics";
import documentReady from "../../utils/documentReady";
(()=>{

    let page = document.querySelector('.product-page');
    if (page) {

        $(document).on('app.start', function () {
            window.sendAnalytics.productDetail(page);
        });

        documentReady(() => {
            $.ajax('/product/api/'+page.dataset.product+'/', {
                'success' : (data)=>{
                    if (data.shipping && data.shipping.free_shipping) {
                        let notification_info = document.querySelectorAll('.notifications-info > .column');
                        if (notification_info.length) {
                            for (let i=0; i < notification_info.length; i++) {
                                notification_info[i].innerHTML += data.shipping.free_shipping;
                            }
                        }

                }
            }
        });

            $('#product_tabs').on('click', '#questions-label', function() {

                console.log(67);

                let container = $('#questions');

                $.ajax('/product-question/', {
                    'data' : {
                        'productId' : container.data('productid')
                    },
                    'success' : (html)=>{
                        if (html) {
                            container.html(html);
                        }
                    }
                });

            });
        });
    }
})();