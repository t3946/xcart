import { ShippingMethods } from './ShippingMethods';
import { PaymentMethods }  from './PaymentMethods';
import Forms       from '_binds/forms';
import BillingForm from "@/js/Components/checkout/BillingForm";

export default ( function () {
    /**
     * prevent update fields with id from this list
     */
    const excludedFieldsFromUpdate = ['CheckoutForm_s_address', 'CheckoutForm_b_address'];
    const $form = $( '.checkout-shipping-form' );
    const $submitButton = $form.find('button[type="submit"]');

    // determine address or part of address field
    function isAddressField(fieldName) {
        // only google autocomplete affected fields
        const addressFieldsNames = [
            'CheckoutForm[s_address]',
            'CheckoutForm[s_full_address]',
            'CheckoutForm[s_city]',
            'CheckoutForm[s_state]',
            'CheckoutForm[s_zipcode]',
            'CheckoutForm[s_country]',
            'CheckoutForm[b_address]',
            'CheckoutForm[b_full_address]',
            'CheckoutForm[b_city]',
            'CheckoutForm[b_state]',
            'CheckoutForm[b_zipcode]',
            'CheckoutForm[b_country]',
        ]
        return addressFieldsNames.indexOf(fieldName) !== -1;
    }

    const Constructor = function () {
        $form.on( 'change', 'input, textarea', function ( e ) {
            const input = e.target;
            const name = input.name;

            //prevent quantity field update
            if (name === 'quantity') {
                return;
            }

            //prevent address fields update -- for address fields have dedicated
            //update system because google autocomplete has not friendly event system
            if (isAddressField(name)) {
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

        /**
         * recount subtotal for cart distributor with shipping cost
         * SERVER PROBLEM:
         * Server always return subtotal without shipping cost thus
         * when page loaded with shipping method need add shipping cost to subtotal
         */
        $('.warehouse_products').each((i, elem) => {
            const $distributorCart = $(elem);
            const $shipping = $distributorCart.find('.shipping-methods-group input:checked');

            //if no shipping methods then subtotal is correct
            if ($shipping.length === 0){
                return;
            }

            const $subtotal = $distributorCart.find('.subtotal');
            const clearSubtotal = $subtotal.text();
            const shippingCost = $shipping.data('shipping-cost');
            const subtotal = parseFloat(clearSubtotal) + parseFloat(shippingCost);

            $subtotal.text(this.formatNumber(subtotal));
        });

        /**
         * по таймеру, каждую секунду проверяется адрес, и если он изменился
         * с последней проверки -- отправить новый адрес
         */
        (() => {
            const delayUpdateMS = 1000;
            let oldAddress = {};
            let changedFields = {};

            function rememberNewAddress () {
                oldAddress = {
                    CheckoutForm_s_address: CheckoutForm_s_address.value,
                    CheckoutForm_s_full_address: CheckoutForm_s_full_address.value,
                    CheckoutForm_s_city: CheckoutForm_s_city.value,
                    CheckoutForm_s_state: CheckoutForm_s_state.value,
                    CheckoutForm_s_zipcode: CheckoutForm_s_zipcode.value,
                    CheckoutForm_s_country: CheckoutForm_s_country.value,
                    CheckoutForm_b_address: CheckoutForm_b_address.value,
                    CheckoutForm_b_full_address: CheckoutForm_b_full_address.value,
                    CheckoutForm_b_city: CheckoutForm_b_city.value,
                    CheckoutForm_b_state: CheckoutForm_b_state.value,
                    CheckoutForm_b_zipcode: CheckoutForm_b_zipcode.value,
                    CheckoutForm_b_country: CheckoutForm_b_country.value,
                }
            }

            function isAddressChanged() {
                changedFields = {};

                for ( const fieldId in oldAddress ) {
                    const field = document.getElementById(fieldId);
                    const oldValue = oldAddress[fieldId];
                    const newValue = field.value;

                    if (oldValue !== newValue) {
                        changedFields[field.name] = newValue;
                    }
                }

                return Object.keys(changedFields).length > 0;
            }

            rememberNewAddress();

            setInterval(() => {
                if (isAddressChanged() === true) {
                    rememberNewAddress();
                    this.fieldUpdate(changedFields);
                }
            },delayUpdateMS);
        })();
    }

    function isCheckoutPage() {
        return document.querySelector( '.checkout-page' ) !== null;
    }

    Constructor.prototype.formatNumber = function( number ) {
        return Intl
            .NumberFormat( 'en-US', { style: 'currency', currency: 'USD' } )
            .format( number )
            .substr( 1 );
    };

    /**
     * Отключить кнопку отправки формы (следует делать во время валидации и запросов к api)
     *
     * @param isDisabled значение для атрибута disabled
    */
    Constructor.prototype.disableSubmitButton = function (isDisabled) {
        $submitButton
            .prop('disabled', isDisabled)
            .toggleClass('button__loading', isDisabled);
    }

    Constructor.prototype.update = function ( data, callback = null ) {
        //duplication firstname from shipping form to contact form
        if (
          typeof CheckoutForm_s_firstname !== "undefined"
          && CheckoutForm_s_firstname.value === CheckoutForm_firstname.value
          && data['CheckoutForm[s_firstname]']
        ) {
            data['CheckoutForm[firstname]'] = data['CheckoutForm[s_firstname]'];
        }

        Constructor.prototype.disableSubmitButton(true);

        const self = this;

        $.ajax( {
            url: '/api/checkout/update',
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function ( res ) {
                if ( res.templates.payment_methods && PaymentMethods ) {
                    PaymentMethods.updateTemplate( res.templates.payment_methods );
                }

                if ( isCheckoutPage() ) {
                    // update shipping methods if templates passed
                    if ( res.templates.shipping_methods ) {
                        ShippingMethods.updateTemplate( res.templates.shipping_methods );
                        $( document ).trigger( 'update.total.checkout', res );
                        BillingForm.init();
                    }

                    for ( let manufacturer_id in res.distributor_carts ) {
                        const manufacturer = res.distributor_carts[ manufacturer_id ];
                        const whTotal = $( `.warehouse_subtotal[data-wh=${ manufacturer_id }]` );

                        const $salesTax = whTotal.find( '.total-sales-tax' );

                        if ( manufacturer[ 'sales_tax' ] ) {
                            $salesTax
                              .show()
                              .find( '.subtotal' )
                              .text( self.formatNumber( manufacturer[ 'sales_tax' ] ) );
                        } else {
                            $salesTax.hide();
                        }

                        const $vaxTax = whTotal.find( '.total-vat-tax' );

                        if ( manufacturer[ 'vat_tax' ] ) {
                            $vaxTax
                              .show()
                              .find( '.subtotal' )
                              .text( self.formatNumber( parseFloat( manufacturer[ 'vat_tax' ] ) ) );
                        } else {
                            $vaxTax.hide();
                        }

                        const deliveryCost = whTotal.closest('.warehouse_products').find('.shipping-methods-group input:checked').data('shipping-cost') || 0;

                        whTotal
                          .find( '.format_price .subtotal' )
                          .text( self.formatNumber( parseFloat( manufacturer[ 'subtotal' ] ) + deliveryCost ) );
                    }
                }

                if ( typeof callback === "function" ) {
                    callback( res );
                }

                $( document ).trigger( 'updateRequestSuccess.checkout', res );
            },
            error: function ( err ) {
                console.log( 'error', err );
            },
            complete() {
                // hide checkout order total preloader
                $( document ).trigger( 'updateRequestComplete.checkout' );

                Constructor.prototype.disableSubmitButton(false);
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