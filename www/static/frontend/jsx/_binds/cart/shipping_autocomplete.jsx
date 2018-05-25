import autoComplete from 'bower_components/javascript-auto-complete/auto-complete.js';

(()=>{
    let page = document.querySelector('.cart_shipping-page');
    if (page) {

         let inputCountry = document.querySelector('#ShippingAddressForm_country');
         //let inputZipCode = document.querySelector('#ShippingAddressForm_zipcode');
         let inputState = document.querySelector('#ShippingAddressForm_state');
         //let inputCity = document.querySelector('#ShippingAddressForm_city');

         //console.info(autoComplete);
        //console.info(window.autoComplete);
        //console.info(window.autoComplete);

        new autoComplete({
            selector: '#ShippingAddressForm_country',
            cache: false,
            offsetTop: 0,
            renderItem: function (item, search){
                search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
                var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
                return '<div class="autocomplete-suggestion" data-val="' + item.name + '" data-code="' + item.code + '">' + item.name.replace(re, "<b>$1</b>") + '</div>';
            },
            source: function(term, suggest){
                $.getJSON('/checkout/auto_complete_country/', { search: term }, function(data){
                    suggest(data);
                });

            },
            onSelect: function(e, term, item){
                let code = item.getAttribute('data-code');
                inputCountry.setAttribute('data-code', code);
            }
        });

        // ['zip', 'primary_city', 'state']
        new autoComplete({
            selector: '#ShippingAddressForm_zipcode',
            cache: false,
            renderItem: function (item, search){
                search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
                let re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
                let html = '<span class="zip">' + item.zip.replace(re, "<b>$1</b>") + '</span>';
                html += ' <span class="city">' + item.primary_city.replace(re, "<b>$1</b>") + ', ';
                html += item.state.replace(re, "<b>$1</b>") + '</span>';

                return '<div class="autocomplete-suggestion" data-val="' + item.zip + '">' + html + '</div>';
            },
            source: function(term, suggest){

                $.getJSON('/checkout/auto_complete_zip_code/', {
                    search: term,
                    country: inputCountry.getAttribute('data-code')
                }, function(data){
                    suggest(data);
                });
            }
        });

        new autoComplete({
            selector: '#ShippingAddressForm_state',
            cache: false,
            renderItem: function (item, search){
                search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
                var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
                return '<div class="autocomplete-suggestion" data-val="' + item.state + '" data-code="' + item.code + '">' + item.state.replace(re, "<b>$1</b>") + '</div>';
            },
            source: function(term, response){
                $.getJSON('/checkout/auto_complete_state/', {
                    search: term,
                    country: inputCountry.getAttribute('data-code')
                }, function(data){
                    response(data);
                });
            },
            onSelect: function(e, term, item){
                let code = item.getAttribute('data-code');
                inputState.setAttribute('data-code', code);
            }
        });

        new autoComplete({
            selector: '#ShippingAddressForm_city',
            cache: false,
            source: function(term, response){
                $.getJSON('/checkout/auto_complete_city/', {
                    search: term,
                    country: inputCountry.getAttribute('data-code'),
                    state: inputState.getAttribute('data-code'),
                }, function(data){
                    response(data);
                });
            }
        });



    }
})();