import { ShippingMethods } from "./ShippingMethods";
import { PaymentMethods } from "./PaymentMethods";
import Forms from '_binds/forms';

export default ( function () {
    if ( document.querySelector( '.checkout-page' ) === null ) {
        return;
    }

    const $form = $( '.checkout-shipping-form' );

    const Constructor = function () {
        $form.on( 'change', 'input', function ( e ) {
            const data = {};
            data[ e.target.name ] = e.target.value;
            Constructor.prototype.update( data );
        } );
    }

    Constructor.prototype.update = function ( data ) {
        const form = Forms.getValidationForms().find( ( item ) => item.form.id === 'CheckoutForm9' );

        //dont send if is invalid
        for ( const dataKey in data ) {
            const input = $( `[name="${ dataKey }"]` )[ 0 ];

            if ( form.checkForm( input ) === false ) {
                delete ( data[ dataKey ] );
            }
        }

        //nothing to send
        if ( Object.keys( data ).length === 0 ) {
            return;
        }

        $.ajax( {
            url: '/api/checkout/update',
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function ( res ) {
                var n = '0';
                $( '.order-total .total .price' ).text( parseFloat( n ).toFixed( 2 ) );
                $( '.shipping-total .price' ).text( parseFloat( n ).toFixed( 2 ) );
                $( '.total-sales-tax .price' ).text( parseFloat( n ).toFixed( 2 ) );
                $( '.total-vat-tax .price' ).text( parseFloat( n ).toFixed( 2 ) );
                $( '.grand-total .price' ).text( parseFloat( n ).toFixed( 2 ) );

                for ( let id in res.distributor_carts ) {
                    const whPrices = res.distributor_carts[ id ];
                    const whTotal = $( `.warehouse_subtotal[data-wh=${ id }]` );

                    whTotal.find( '.total-sales-tax .subtotal' ).text( parseFloat( n ).toFixed() );
                    whTotal.find( '.total-vat-tax .subtotal' ).text( parseFloat( n ).toFixed() );
                    whTotal.find( '.format_price .subtotal' ).text( parseFloat( n ).toFixed() );
                }

                if ( res.templates.payment_methods ) {
                    PaymentMethods.updateTemplate( res.templates.payment_methods );
                }

                if ( res.templates.shipping_methods ) {
                    ShippingMethods.updateTemplate( res.templates.shipping_methods );
                }
            },
            error: function ( err ) {
                console.log( 'error', err );
            },
        } );
    }

    return new Constructor();
} )();