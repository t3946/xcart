import { SwitcherButton }             from 'Classes/SwitcherButton';
import { ShippingGoogleAutoComplete } from 'Classes/ShippingGoogleAutoComplete';
import 'node_modules/imask';

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

    /**
     * check mask on field if it exists
     * @param fieldId
     * @param mask
     */
    function setMask( fieldId, mask ) {
        const elem = document.getElementById( fieldId );

        if (typeof elem !== undefined) {
            IMask( elem, { mask } );
        }
    }

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

    setMask( 'CheckoutForm_phone', '(000) 000-0000' );
    setMask( 'CheckoutForm_phone_ext', '00000' );

    return new constructor();
} )();