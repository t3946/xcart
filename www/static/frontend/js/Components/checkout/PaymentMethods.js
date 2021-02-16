export const PaymentMethods = ( function () {
    const selectedClass = 'payment-method-item_selected';

    // no checkout page
    if ( document.querySelector( '.checkout-page' ) === null ) {
        return;
    }

    const $root = $( '.checkout-payment-methods' );
    const $paymentMethods = $root.find( '.payment-method-item' );
    const $radioInputMethods = $root.find( 'input[name=payment_method]' );
    const $allLongDescriptions = $paymentMethods.find( '.payment-method-description-long' );

    $paymentMethods.click( function ( e ) {
        const $paymentMethodItem = $( this );
        const $input = $paymentMethodItem.find( '[name=payment_method]' );

        if ( $input.prop( 'checked' ) === false ) {
            $paymentMethods.removeClass( selectedClass );

            $allLongDescriptions
                .stop( false, true )
                .slideUp();

            $paymentMethodItem
                .addClass( selectedClass )
                .find( '.payment-method-description-long' )
                .stop( false, true )
                .slideDown();
        }

        $input.prop( 'checked', true );
    } );

    $radioInputMethods.filter( ':checked' ).parent().parent().parent().find( '.payment-method-description-long' ).show();
} )();