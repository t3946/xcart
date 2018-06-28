import documentReady from "../../utils/documentReady";
(()=>{

    let page = document.querySelector('.product-page');
    if (page) {


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

                $.ajax('/product-question/', {
                    'success' : (data)=>{
                        if (data) {
                            $('#questions').html(data);
                        }
                    }
                });

            });
        });



    }
})();