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
            offsetTop: 0,
            source: function(term, suggest){
                $.getJSON('/checkout/auto_complete_country/', { search: term }, function(data){
                    suggest(data);
                });
            }
        });

        new autoComplete({
            selector: '#ShippingAddressForm_zipcode',
            source: function(term, suggest){
                $.getJSON('/checkout/auto_complete_zip_code/', { search: term }, function(data){

                    let result = [];
                    for (let item of data) {
                        //result.push('<span class="code">' + item[0] + '</span><span class="city">' + item[1] + '</span><span class="state">' + item[1] + '</span>');
                        result.push(item[0]);
                    }

                    suggest(result);
                });
            }
        });

        new autoComplete({
            selector: '#ShippingAddressForm_state',
            source: function(term, response){
                $.getJSON('/checkout/auto_complete_state/', { search: term }, function(data){
                    response(data);
                });
            }
        });

        new autoComplete({
            selector: '#ShippingAddressForm_city',
            source: function(term, response){
                $.getJSON('/checkout/auto_complete_city/', { search: term }, function(data){
                    response(data);
                });
            }
        });



    }
})();