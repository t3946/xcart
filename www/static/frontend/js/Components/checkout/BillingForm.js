import { SwitcherButton } from "Classes/SwitcherButton";
import { ShippingGoogleAutoComplete } from "Classes/ShippingGoogleAutoComplete";
import "node_modules/imask";

export const BillingForm = ( function () {
    // no checkout page
    if ( document.querySelector( '.checkout-page' ) === null ) {
        return;
    }

    const $otherFields = $( '.checkout-billing-other-fields' );
    let $switcher = null;

    const constructor = function () {
        $switcher = new SwitcherButton( '.address-switcher-button', function () {
            $otherFields.stop( true, false ).slideDown();
        }, function () {
            $otherFields.stop( true, false ).slideUp();
        }, null );
    }

    constructor.prototype.showFields = function () {
        $switcher.isOn = true;
        $otherFields.stop( true, false ).slideDown();
    };

    const componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        locality: 'short_name',
        administrative_area_level_1: 'long_name',
        country: 'long_name',
        postal_code: 'short_name',
    };

    const billing_fields = {
        locality: '#CheckoutForm_b_city',
        administrative_area_level_1: '#CheckoutForm_b_state',
        country: '#CheckoutForm_b_country',
        postal_code: '#CheckoutForm_b_zipcode',
    };

    if ( $( '#CheckoutForm_b_address' ).length ) {
        new ShippingGoogleAutoComplete( '#CheckoutForm_b_address', componentForm, billing_fields );
    }

    IMask( document.getElementById('CheckoutForm_ap_phone'), { mask: '(000) 000-0000' } );
    IMask( document.getElementById('CheckoutForm_pm_phone'), { mask: '(000) 000-0000' } );
    IMask( document.getElementById('CheckoutForm_pm_fax'), { mask: '(000) 000-0000' } );
    IMask( document.getElementById('CheckoutForm_ci_phone_ext'), { mask: '00000' } );

    return new constructor();
} )();