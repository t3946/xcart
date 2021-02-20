import { SwitcherSlider } from "Classes/SwitcherSlider";

export const BillingSameShipping = ( function () {
    // no checkout page
    if ( document.querySelector( '.checkout-page' ) === null ) {
        return;
    }

    const $addressFields = $( '.billing-form-address-fields' );

    new SwitcherSlider(
        $( '.switcher-slider-label' ),
        function () {
            $addressFields.stop( true, false ).slideDown();
        },
        function () {
            $addressFields.stop( true, false ).slideUp();
        },
    );
} )();