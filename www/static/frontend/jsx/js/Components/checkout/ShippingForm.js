import { ShippingPixabayAutocomplete } from "@/js/Classes/ShippingPixabayAutocomplete";
import { ShippingGoogleAutoComplete } from "@/js/Classes/ShippingGoogleAutoComplete";
import { SwitcherButton } from "@/js/Classes/SwitcherButton";
import "node_modules/imask";

export const ShippingForm = ( function () {
    let switcher = null;
    // no checkout page
    if ( document.querySelector( '.checkout-page' ) === null ) {
        return;
    }

    const constructor = function () {
        const self = this;

        this.$otherFields = $( '.checkout-shipping-other-fields' );
        this.addressField = document.getElementById( 'CheckoutForm_s_address' );

        switcher = new SwitcherButton( '.shipping-switcher-button', function () {
            self.$otherFields.stop( true, false ).slideDown();
        }, function () {
            self.$otherFields.stop( true, false ).slideUp();
        } );

        /* phone mask */
        const CheckoutForm_ci_phone = document.getElementById('CheckoutForm_phone');
        CheckoutForm_ci_phone && IMask( CheckoutForm_ci_phone, { mask: '(000) 000-0000' } );

        const CheckoutForm_ci_phone_ext = document.getElementById('CheckoutForm_phone_ext');
        CheckoutForm_ci_phone_ext && IMask( CheckoutForm_ci_phone_ext, { mask: '00000' } );
    }

    constructor.prototype.showFields = function () {
        switcher.isOn = true;
        this.$otherFields.stop( true, false ).slideDown();
    }

    // autocomplete for main address field
    const componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        locality: 'short_name',
        administrative_area_level_1: 'long_name',
        country: 'long_name',
        postal_code: 'short_name',
    };

    const shipping_fields = {
        locality: '#CheckoutForm_s_city',
        administrative_area_level_1: '#CheckoutForm_s_state',
        country: '#CheckoutForm_s_country',
        postal_code: '#CheckoutForm_s_zipcode',
    };

    new ShippingGoogleAutoComplete( '#CheckoutForm_s_full_address', componentForm, shipping_fields );

    /**
     * init autocomplete for group address fields (country state city zipcode)
     */
    function initAddressAutocompleteFields( countryInput, stateInput, cityInput, zipcodeInput ) {
        new ShippingPixabayAutocomplete( countryInput, {
            renderItem: function ( item, search ) {
                return '<div class="autocomplete-suggestion" data-val="' + item.name + '" data-code="' + item.code + '">' + item.name + '</div>';
            },
            source: function ( term, suggest ) {
                $.getJSON( '/checkout/auto_complete_country/', { search: term }, function ( data ) {
                    suggest( data );
                } );
            },
        } );

        new ShippingPixabayAutocomplete( stateInput, {
            renderItem: function ( item, search ) {
                return '<div class="autocomplete-suggestion" data-val="' + item.state + '" data-code="' + item.code + '">' + item.state + '</div>';
            },
            source: function ( term, suggest ) {
                $.getJSON( '/checkout/auto_complete_state/', {
                    search: term,
                    country: countryInput.dataset.code,
                }, function ( data ) {
                    suggest( data );
                } );
            },
            onSelect: function ( e, term, item, ctx ) {
                let state, codeState;

                if ( e.constructor === Object ) {
                    state = e.state;
                    codeState = e.code;
                } else {
                    state = item.dataset.val;
                    codeState = item.dataset.code;
                }

                stateInput.value = state;
                stateInput.dataset.code = codeState
            }
        } );

        new ShippingPixabayAutocomplete( cityInput, {
            renderItem: function ( item, search ) {
                return '<div class="autocomplete-suggestion" data-val="' + item + '">' + item + '</div>';
            },
            source: function ( term, suggest ) {
                $.getJSON( '/checkout/auto_complete_city/', {
                    search: term,
                    country: countryInput ? countryInput.getAttribute( 'data-code' ) : '',
                    state: CheckoutForm_s_state ? CheckoutForm_s_state.getAttribute( 'data-code' ) : '',
                }, function ( data ) {
                    suggest( data );
                } );
            },
        } );

        new ShippingPixabayAutocomplete( zipcodeInput, {
            renderItem: function ( item, search ) {
                let html = '<span class="zip">' + item.zip + '</span>' + ' <span class="city">' + item.primary_city + ', ' + item.state + '</span>';

                return '<div class="autocomplete-suggestion" data-state-name="' + item.state_name + '" data-state="' + item.state + '" data-city="' + item.primary_city + '"  data-val="' + item.zip + '">' + html + '</div>';
            },
            source: function ( term, suggest ) {
                $.getJSON( '/checkout/auto_complete_zip_code/', {
                    search: term,
                    country: countryInput ? countryInput.getAttribute( 'data-code' ) : '',
                }, function ( data ) {
                    suggest( data );
                } );
            },
            onSelect: function ( e, term, item, ctx ) {
                let city, stateName, stateCode, zipCode;

                if ( e.constructor === Object ) {
                    city = e.primary_city;
                    stateName = e.state_name;
                    stateCode = e.state;
                    zipCode = e.zip;
                } else {
                    e.preventDefault();
                    city = item.dataset.city;
                    stateName = item.dataset.stateName;
                    stateCode = item.dataset.state;
                    zipCode = item.dataset.val;
                }

                zipcodeInput.value = zipCode;
                cityInput.value = city;
                stateInput.value = stateName;
                stateInput.dataset.code = stateCode;

                ctx.throwJsChangeEvent( zipcodeInput );
                ctx.throwJsChangeEvent( cityInput );
                ctx.throwJsChangeEvent( stateInput );
            }
        } );
    }

    /**
     * shipping address feilds
     */
    initAddressAutocompleteFields( CheckoutForm_s_country, CheckoutForm_s_state, CheckoutForm_s_city, CheckoutForm_s_zipcode );

    /**
     * billing address feilds
     */
    if ( typeof CheckoutForm_b_country !== 'undefined'
        && typeof CheckoutForm_b_state !== 'undefined'
        && typeof CheckoutForm_b_city !== 'undefined'
        && typeof CheckoutForm_b_zipcode !== 'undefined'
    ) {
        initAddressAutocompleteFields( CheckoutForm_b_country, CheckoutForm_b_state, CheckoutForm_b_city, CheckoutForm_b_zipcode );
    }

    return new constructor();
} )();