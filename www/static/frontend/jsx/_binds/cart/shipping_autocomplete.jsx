import autoComplete from 'bower_components/javascript-auto-complete/auto-complete.js';

(()=>{
    let page = document.querySelector('.cart_shipping-page');
    if (page) {

        // let inputCountry = document.querySelector('#ShippingAddressForm_country');
        // let inputZipCode = document.querySelector('#ShippingAddressForm_zipcode');
        // let inputState = document.querySelector('#ShippingAddressForm_state');
        // let inputCity = document.querySelector('#ShippingAddressForm_city');

         //console.info(autoComplete);
        //console.info(window.autoComplete);
        //console.info(window.autoComplete);

        new autoComplete({
            selector: '#ShippingAddressForm_country',
            source: function(term, response){
                $.getJSON('/checkout/auto_complete_country/', { q: term }, function(data){
                    //response(data);
                    console.info(data);
                    response([]);
                });
            }
        });

        new autoComplete({
            selector: '#ShippingAddressForm_zipcode',
            source: function(term, response){
                $.getJSON('/checkout/auto_complete_zip_code/', { q: term }, function(data){
                    //response(data);
                    console.info(data);
                    response([]);
                });
            }
        });

        new autoComplete({
            selector: '#ShippingAddressForm_state',
            source: function(term, response){
                $.getJSON('/checkout/auto_complete_state/', { q: term }, function(data){
                    //response(data);
                    console.info(data);
                    response([]);
                });
            }
        });

        new autoComplete({
            selector: '#ShippingAddressForm_city',
            source: function(term, response){
                $.getJSON('/checkout/auto_complete_city/', { q: term }, function(data){
                    //response(data);
                    console.info(data);
                    response([]);
                });
            }
        });



    }
})();