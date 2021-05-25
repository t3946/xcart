import { SwitcherButton }             from '@/js/Classes/SwitcherButton';
import { ShippingGoogleAutoComplete } from '@/js/Classes/ShippingGoogleAutoComplete';
import 'node_modules/imask';

export default ( function BillingForm() {
    // no checkout page
    if ( document.querySelector( '.checkout-page' ) === null ) {
        return;
    }

    let $otherFields = $( '.checkout-billing-other-fields' );
    let $switcher = null;

    const constructor = function () {
        this.init();
        this.updateSwitcher();
    }

    constructor.prototype.showFields = function () {
        $switcher.isOn = true;
        $otherFields.stop( true, false ).slideDown();
    };

    constructor.prototype.updateSwitcher = function() {
        $otherFields = $( '.checkout-billing-other-fields' );

        $switcher = new SwitcherButton( '.address-switcher-button', function () {
            $otherFields.stop( true, false ).slideDown();
        }, function () {
            $otherFields.stop( true, false ).slideUp();
        }, null );
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

    constructor.prototype.init = function() {
        console.log('init billing form');
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
            new ShippingGoogleAutoComplete( '#CheckoutForm_b_full_address', componentForm, billing_fields );
        }

        setMask( 'CheckoutForm_phone', '(000) 000-0000' );
        setMask( 'CheckoutForm_phone_ext', '00000' );
        setMask( 'CheckoutForm_pm_phone', '(000) 000-0000' );
        setMask( 'CheckoutForm_pm_fax', '(000) 000-0000' );
        setMask( 'CheckoutForm_ap_phone', '(000) 000-0000' );
    }


    return new constructor();
} )();