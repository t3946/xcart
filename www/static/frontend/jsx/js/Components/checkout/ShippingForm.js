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

    return new constructor();
} )();