import { ShippingMethods } from './ShippingMethods';
import { PaymentMethods }  from './PaymentMethods';
import Forms               from '_binds/forms';

export default ( function () {
    // number update queries on update product quantity
    let quantityUpdateQueries = 0;

    if ( document.querySelector( '.checkout-page' ) === null ) {
        return;
    }

    /**
     * prevent update fields with id from this list
     */
    const excludedFieldsFromUpdate = ['CheckoutForm_s_address', 'CheckoutForm_b_address'];
    const $form = $( '.checkout-shipping-form' );

    const Constructor = function () {
        $form.on( 'change', 'input, textarea', function ( e ) {
            const input = e.target;

            //prevent quantity field update
            if (input.name === 'quantity') {
                return;
            }

            let value;

            switch ( input.type ) {
                case 'checkbox':
                    value = input.checked ? 1 : 0;
                    break;
                default:
                    value = input.value;
                    break;
            }

            const data = {};
            data[ input.name ] = value;

            if (excludedFieldsFromUpdate.indexOf(e.target.id) === -1) {
                Constructor.prototype.update( data );
            }
        } );
    }

    Constructor.prototype.formatNumber = function( number ) {
        return Intl
            .NumberFormat( 'en-US', { style: 'currency', currency: 'USD' } )
            .format( number )
            .substr( 1 );
    };

    Constructor.prototype.update = function ( data, callback = null ) {
        const self = this;

        // show checkout order total preloader
        if ( data.quantity !== undefined ) {
            quantityUpdateQueries += 1;

            if (quantityUpdateQueries > 0) {
                $('.order-total-wrapper').addClass('order-total-wrapper__loading');
                $('.order-total_preloader').fadeIn();
            }
        }

        $.ajax( {
            url: '/api/checkout/update',
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function ( res ) {
                $( '.order-total .total .price' ).text( self.formatNumber( res[ 'total' ] ) );
                $( '.shipping-total .price' ).text( self.formatNumber( res[ 'total_shipping_cost' ] ) );
                $( '.total-sales-tax .price' ).text( self.formatNumber( res[ 'total_sales_tax' ] ) );
                $( '.total-vat-tax .price' ).text( self.formatNumber( res[ 'total_vat_tax' ] ) );
                $( '.grand-total .price' ).text( self.formatNumber( res[ 'grand_total' ] ) );

                for ( let manufacturer_id in res.distributor_carts ) {
                    const manufacturer = res.distributor_carts[ manufacturer_id ];
                    const whTotal = $( `.warehouse_subtotal[data-wh=${ manufacturer_id }]` );

                    const $salesTax = whTotal.find( '.total-sales-tax' );

                    if ( manufacturer[ 'sales_tax' ] ) {
                        $salesTax
                            .show()
                            .find( '.subtotal' )
                            .text( parseFloat( manufacturer[ 'sales_tax' ] ).toFixed( 2 ) );
                    } else {
                        $salesTax.hide();
                    }

                    const $vaxTax = whTotal.find( '.total-vat-tax' );

                    if ( manufacturer[ 'vat_tax' ] ) {
                        $vaxTax
                            .show()
                            .find( '.subtotal' )
                            .text( parseFloat( manufacturer[ 'vat_tax' ] ).toFixed( 2 ) );
                    } else {
                        $vaxTax.hide();
                    }

                    whTotal.find( '.format_price .subtotal' ).text( parseFloat( manufacturer['subtotal'] ).toFixed(2) );
                }

                if ( res.templates.payment_methods ) {
                    PaymentMethods.updateTemplate( res.templates.payment_methods );
                }

                if ( res.templates.shipping_methods ) {
                    ShippingMethods.updateTemplate( res.templates.shipping_methods );
                }

                if ( typeof callback === "function" ) {
                    callback( res );
                }
            },
            error: function ( err ) {
                console.log( 'error', err );
            },
            complete() {
                // hide checkout order total preloader
                if ( data.quantity !== undefined ) {
                    quantityUpdateQueries -= 1;

                    if (quantityUpdateQueries === 0) {
                        $('.order-total-wrapper').removeClass('order-total-wrapper__loading');
                        $('.order-total_preloader').fadeOut();
                    }
                }
            },
        } );
    }

    Constructor.prototype.fieldUpdate = function ( data, callback = null ) {
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

        this.update( data );
    }

    return new Constructor();
} )();