export default ( function () {
    const $orderTotal = $( '.order-total' );
    const $shippingPriceTotal = $orderTotal.find( '.shipping-total .price' );

    function countShippingTotal() {
        let totalShippingPrice = 0;

        $( '.shipping-methods-group' ).each( function ( i, elem ) {
            const costText = $( elem ).find( 'input:checked' ).parent().find( '.cost' ).text();

            totalShippingPrice += parseFloat( costText.split( ' ' )[ 1 ] );
        } );

        $shippingPriceTotal.text( totalShippingPrice.toFixed(2) );
    }

    const constructor = function () {
        $( document ).on( 'delivery-address-update', countShippingTotal );
    }

    countShippingTotal();

    return new constructor();
} )();