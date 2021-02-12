export const PaymentMethods = ( function () {
    const selectedClass = 'payment-method-item_selected';

    // no checkout page
    if ( document.querySelector( '.checkout-page' ) === null ) {
        return;
    }

    const $root = $( '.checkout-payment-methods' );
    const $paymentMethods = $root.find( '.payment-method-item' );
    const $radioInputMethods = $root.find( 'input[name=payment_method]' );

    $paymentMethods.click( function ( e ) {
        const $this = $( this );

        if ( $this.hasClass( selectedClass ) ) {
            e.preventDefault();
            e.stopPropagation();
        }
    } );

    $radioInputMethods.change( function () {
        const $this = $( this );

        $paymentMethods.removeClass( selectedClass );

        $root.find( '.payment-method-description-long' ).clear( false, true ).slideUp();

        const $description = $this.parent().parent().parent();
        $description.find( '.payment-method-description-long' ).clear( false, true ).slideDown();

        const $paymentMethod = $description.parent();
        $paymentMethod.addClass( selectedClass );
    } );

    $radioInputMethods.filter( ':checked' ).parent().parent().parent().find( '.payment-method-description-long' ).show();
} )();