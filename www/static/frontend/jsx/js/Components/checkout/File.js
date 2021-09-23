/**
 * attach original po file input
 */

export default ( () => {
    const $input = $( '#CheckoutForm_purchase_order_file' );

    $input.change( () => {
        const fd = new FormData( document.forms.CheckoutForm9 );

        $.ajax( {
            url: '/api/checkout/update',
            data: fd,
            success( res ) {
                console.log( res );
            },
            error( err ) {
                console.log( err );
            },
        } );

        console.log(fd);
    } );
} )();
