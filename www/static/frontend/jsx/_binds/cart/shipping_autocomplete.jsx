import autoComplete from 'bower_components/javascript-auto-complete/auto-complete.js';

(()=>{
    //checkout-shipping
    let elExists = document.querySelector('.auto-complete');
    if (elExists) {

         let inputCountry = document.querySelector('.auto-complete.country');
         let inputZipCode = document.querySelector('.auto-complete.zip');
         let inputState = document.querySelector('.auto-complete.state');
         let inputCity = document.querySelector('.auto-complete.city');

        if(inputCountry){
            new autoComplete({
                selector: inputCountry,
                cache: false,
                offsetTop: 0,
                renderItem: function (item, search){
                    search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
                    var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
                    return '<div class="autocomplete-suggestion" data-val="' + item.name + '" data-code="'
                        + item.code + '">' + item.name.replace(re, "<b>$1</b>") + '</div>';
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
        }

        if(inputZipCode){
            new autoComplete({
                selector: inputZipCode,
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
                        country: inputCountry ? inputCountry.getAttribute('data-code') : ''
                    }, function(data){
                        suggest(data);
                    });
                }
            });
        }

        if(inputState){
            new autoComplete({
                selector: inputState,
                cache: false,
                renderItem: function (item, search){
                    search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
                    var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
                    return '<div class="autocomplete-suggestion" data-val="' + item.state + '" data-code="' + item.code + '">' + item.state.replace(re, "<b>$1</b>") + '</div>';
                },
                source: function(term, response){
                    $.getJSON('/checkout/auto_complete_state/', {
                        search: term,
                        country: inputCountry ? inputCountry.getAttribute('data-code') : ''
                    }, function(data){
                        response(data);
                    });
                },
                onSelect: function(e, term, item){
                    let code = item.getAttribute('data-code');
                    inputState.setAttribute('data-code', code);
                }
            });
        }

        if(inputCity){
            new autoComplete({
                selector: inputCity,
                cache: false,
                source: function(term, response){
                    $.getJSON('/checkout/auto_complete_city/', {
                        search: term,
                        country: inputCountry ? inputCountry.getAttribute('data-code') : '',
                        state: inputState ? inputState.getAttribute('data-code') : '',
                    }, function(data){
                        response(data);
                    });
                }
            });
        }




    }
})();