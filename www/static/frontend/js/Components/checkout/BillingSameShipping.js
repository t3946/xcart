import { SwitcherSlider } from "Classes/SwitcherSlider";

export const BillingSameShipping = ( function () {
    // no checkout page
    if ( document.querySelector( '.checkout-page' ) === null ) {
        return;
    }

    const $addressFields = $( '.billing-form-address-fields' );

    new SwitcherSlider(
        $( '.billing-same-shipping-switcher' ),
        function () {
            $addressFields.stop( true, false ).slideDown();
        },
        function () {
            $addressFields.stop( true, false ).slideUp();
        },
    );
} )();