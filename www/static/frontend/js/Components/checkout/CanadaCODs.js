export const CanadaCods = ( function () {
    const $target = $( '.checkout__canada-cods-field' );
    const $destDesktop = $( '.order-total__grand' );
    const $destMobile = $( '#CheckoutForm_ci_email' ).parents( '.checkout-field-row' );
    const $countryField = $( '#CheckoutForm_s_country' );
    let active = false;

    // show if country is Canada else hide
    function toggle() {
        active = $countryField.val().toLowerCase().trim() === 'canada';
        $target.toggle( active, 0 );
    }

    function insert() {
        $target.insertAfter( innerWidth >= 1024 ? $destDesktop : $destMobile );
    }

    const constructor = function () {
        if ( !$('.checkout-page').length ){
            return;
        }

        $( document ).on( 'window_resize', function ( e ) {
            insert();
        } );

        $countryField.change( toggle );

        toggle();
        insert();
    }

    constructor.prototype.isActive = function () {
        return active;
    }

    return new constructor();
} )();
