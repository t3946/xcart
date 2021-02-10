export const PaymentMethods = ( function () {
    // no checkout page
    if ( document.querySelector( '.checkout-page' ) === null ) {
        return;
    }

    const $root = $( '.checkout-payment-methods' );
    const $paymentMethods = $root.find( '.payment-method-item' );
    const $radioInputMethods = $root.find( 'input[name=payment_method]' );

    $radioInputMethods.change( function () {
        const $this = $( this );
        $root.find( '.payment-method-description-long' ).slideUp();
        $this.parent().parent().parent().find( '.payment-method-description-long' ).slideDown();
        // убрать палец если active $paymentMethod
    } );

    $radioInputMethods.filter( ':checked' ).parent().parent().parent().find( '.payment-method-description-long' ).show();
} )();