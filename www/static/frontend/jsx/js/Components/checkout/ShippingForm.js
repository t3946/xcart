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

    return new constructor();
} )();