export const CanadaCODs = ( function () {
    const $target = $( '.checkout__canada-cods-field' );
    const $destDesktop = $( '.order-total' );
    const $destMobile = $( '#CheckoutForm_email' ).parents( '.checkout-field-row' );
    const $countryField = $( '#CheckoutForm_s_country' );
    let active = false;

    // show if country is Canada else hide
    function toggle() {
        active = $countryField.val().toLowerCase().trim() === 'canada';

        if (active) {
            $target.removeClass('hide');
        } else {
            $target.addClass('hide');
        }
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

        insert();
        toggle();
    }

    constructor.prototype.isActive = function () {
        return active;
    }

    return new constructor();
} )();
