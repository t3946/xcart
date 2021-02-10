export const BillingSameShipping = ( function () {
    // no checkout page
    if ( document.querySelector( '.checkout-page' ) === null ) {
        return;
    }

    const $root = $( '.billing-form-fields' );
    const $addressFields = $( '.billing-form-address-fields' );

    function toggleAddressFields( value ) {
        value === '0' ? $addressFields.slideDown() : $addressFields.slideUp();
    }

    $root.find( 'input[name=billing_same]' ).change( function ( e ) {
        toggleAddressFields( e.target.value );
    } );
} )();