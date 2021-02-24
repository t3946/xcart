export const ShippingMethods = ( function () {
    function updateClasses( $group ) {
        $group
            .find( '.shipping-method-row' )
            .removeClass( 'shipping-method-row_selected' )
            .find( 'input:checked' )
            .parents( '.shipping-method-row' )
            .addClass( 'shipping-method-row_selected' );
    }

    $( '.shipping-methods-group' ).on('change', 'input', function () {
        updateClasses( $( this ).parents( '.shipping-methods-group' ) );
    });

    $( '.shipping-methods-group' ).each( function ( i, elem ) {
        updateClasses( $( elem ) );
    } );
} )();