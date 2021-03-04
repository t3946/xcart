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
                $( '.order-total .total .price' ).text( res.total );
                $( '.shipping-total .price' ).text( res.total_shipping_cost );
                $( '.total-sales-tax .price' ).text( res.total_sales_tax );
                $( '.total-vat-tax .price' ).text( res.total_vat_tax );
                $( '.grand-total .price' ).text( res.grand_total );

                for ( let id in res.distributor_carts ) {
                    const whPrices = res.distributor_carts[ id ];
                    const whTotal = $( `.warehouse_subtotal[data-wh=${ id }]` );

                    whTotal.find( '.total-sales-tax .subtotal' ).text( whPrices.sales_tax );
                    whTotal.find( '.total-vat-tax .subtotal' ).text( whPrices.vat_tax );
                    whTotal.find( '.format_price .subtotal' ).text( whPrices.subtotal );
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