import autoComplete from 'bower_components/javascript-auto-complete/auto-complete.js';


    var createAutoComplete = function(){

        function throwJsChangeEvent(element){
            //console.log('throwJsChangeEvent');
            let detail = {
                'element' : element
            };
            let event = new CustomEvent('js.change.event', { detail: detail });
            element.dispatchEvent(event);
        }

        function createAutoComplete(containerSelector){

            let selectorPrefix = containerSelector ? containerSelector + ' .auto-complete' : '.auto-complete';
            let elExists = document.querySelector(selectorPrefix);

            if (elExists) {

                let inputCountry = document.querySelector(selectorPrefix + '.country');
                let inputZipCode = document.querySelector(selectorPrefix + '.zip');
                let inputState = document.querySelector(selectorPrefix + '.state');
                let inputCity = document.querySelector(selectorPrefix + '.city');

                if (inputCountry) {
                    //console.info(inputCountry);
                    new autoComplete({
                        selector: inputCountry,
                        cache: false,
                        offsetTop: 0,
                        minChars: 1,
                        renderItem: function (item, search) {
                            //search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
                            //var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
                            return '<div class="autocomplete-suggestion" data-val="' + item.name + '" data-code="'
                                + item.code + '">' + item.name + '</div>';
                        },
                        source: function (term, suggest) {
                            //console.info(term);
                            $.getJSON('/checkout/auto_complete_country/', {search: term}, function (data) {
                                //console.info(data);
                                suggest(data);
                            });
                        },
                        onSelect: function (e, term, item) {
                            e.preventDefault();
                            let code = item.getAttribute('data-code');
                            inputCountry.setAttribute('data-code', code);
                        }
                    });
                }

                if (inputZipCode) {
                    new autoComplete({
                        selector: inputZipCode,
                        cache: false,
                        minChars: 1,
                        renderItem: function (item, search) {
                            //search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
                            // let re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
                            let html = '<span class="zip">' + item.zip + '</span>';
                            html += ' <span class="city">' + item.primary_city + ', ';
                            html += item.state + '</span>';

                            return '<div class="autocomplete-suggestion" data-state-name="'+item.state_name+'" data-state="'+item.state+'" data-city="'+item.primary_city+'"  data-val="' + item.zip + '">' + html + '</div>';
                        },
                        source: function (term, suggest) {

                            $.getJSON('/checkout/auto_complete_zip_code/', {
                                search: term,
                                country: inputCountry ? inputCountry.getAttribute('data-code') : ''
                            }, function (data) {
                                suggest(data);
                            });
                        },
                        onSelect: function (e, term, item) {
                            e.preventDefault();

                            inputCity.value = item.getAttribute('data-city');
                            inputState.value = item.getAttribute('data-state-name');

                            throwJsChangeEvent(inputCity);
                            throwJsChangeEvent(inputState);

                            inputState.setAttribute('data-code', item.getAttribute('data-state'));
                        }
                    });
                }

                if (inputState) {
                    new autoComplete({
                        selector: inputState,
                        cache: false,
                        minChars: 1,
                        renderItem: function (item, search) {
                            //search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
                            //var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
                            return '<div class="autocomplete-suggestion" data-val="' + item.state + '" data-code="' + item.code + '">'
                                + item.state + '</div>';
                        },
                        source: function (term, response) {
                            $.getJSON('/checkout/auto_complete_state/', {
                                search: term,
                                country: inputCountry ? inputCountry.getAttribute('data-code') : ''
                            }, function (data) {
                                response(data);
                            });
                        },
                        onSelect: function (e, term, item) {
                            e.preventDefault();
                            let code = item.getAttribute('data-code');
                            inputState.setAttribute('data-code', code);

                        }
                    });
                }

                if (inputCity) {
                    new autoComplete({
                        selector: inputCity,
                        cache: false,
                        minChars: 1,
                        source: function (term, response) {
                            $.getJSON('/checkout/auto_complete_city/', {
                                search: term,
                                country: inputCountry ? inputCountry.getAttribute('data-code') : '',
                                state: inputState ? inputState.getAttribute('data-code') : '',
                            }, function (data) {
                                response(data);
                            });
                        },
                        renderItem: function (item, search){
                            //search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
                            //var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
                            return '<div class="autocomplete-suggestion" data-val="' + item + '">' + item + '</div>';
                        },
                        onSelect: function (e, term, item) {
                            e.preventDefault();
                        }
                    });
                }

            }
        }
        return createAutoComplete;
    }();
(() => {
    module.exports = createAutoComplete;
    createAutoComplete();
})();