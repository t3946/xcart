import FormValidation   from '@/js/Classes/FormValidation';
import { ShippingForm } from '@/js/Components/checkout/ShippingForm';
import BillingForm      from '@/js/Components/checkout/BillingForm';
import { CanadaCODs }   from '@/js/Components/checkout/CanadaCODs';

export default class CheckoutFormValidation extends FormValidation {
    constructor( name ) {
        super( name );
    }

    // show wrong fields if it is hide
    onAfterValidatingFields( validationFields ) {
        // shipping address
        if (
            validationFields[ 'CheckoutForm[s_country]' ]
            || validationFields[ 'CheckoutForm[s_zipcode]' ]
            || validationFields[ 'CheckoutForm[s_state]' ]
            || validationFields[ 'CheckoutForm[s_city]' ]
        ) {
            ShippingForm.showFields();
        }

        // billing address
        if (
            validationFields[ 'CheckoutForm[b_country]' ]
            || validationFields[ 'CheckoutForm[b_zipcode]' ]
            || validationFields[ 'CheckoutForm[b_state]' ]
            || validationFields[ 'CheckoutForm[b_city]' ]
        ) {
            BillingForm.showFields();
        }
    }

    getFieldValue( fieldName ) {
        return document.forms[ 'CheckoutForm9' ][ fieldName ].value;
    }

    /**
     * get fields that must be validated
     * @return {{}}
     */
    getValidatingFields() {
        const self = this;
        const $paymentMethodInput = $( '[name="CheckoutForm[paymentid]"]:checked' );
        const $paymentMethodItem = $paymentMethodInput.parents( '.payment-method-item' );
        const selectedPaymentFields = [];

        $paymentMethodItem.find( 'input' ).each( function( i, field ) {
            const fieldName = field.name;

            // billing address without "billing same shipping" mode
            if (
                fieldName.indexOf( '[b_' ) > -1
                && self.fields[ 'CheckoutForm[billing_same_shipping]' ].element.checked === false
            ) {
                return;
            }

            //purchase order fields
            const purchaseFieldNames = [ 'CheckoutForm[po_number]', 'CheckoutForm[po_organization_name]', 'CheckoutForm[pm_firstname]', 'CheckoutForm[pm_phone]', 'CheckoutForm[pm_email]', 'CheckoutForm[ap_firstname]', 'CheckoutForm[ap_phone]', 'CheckoutForm[ap_email]' ];
            const selectedPaymentMethodId = parseInt( self.getFieldValue( 'CheckoutForm[paymentid]' ) );
            const purchaseOrderMethodId = 2;

            if (
                // field from purchase order form
                purchaseFieldNames.indexOf( fieldName ) !== -1
                // purchase order form is disable
                && selectedPaymentMethodId !== purchaseOrderMethodId
            ) {
                return;
            }

            if ( fieldName === 'payment_method' ) {
                return;
            }

            selectedPaymentFields.push( fieldName );
        } );

        const validationFields = {};

        for ( let key in this.fields ) {
            if ( !this.fields.hasOwnProperty( key ) ) {
                continue;
            }

            this.fields[ key ].removeError();

            //shipping fields
            if (
                key.indexOf( '[s_' ) > -1
                || key === 'CheckoutForm[firstname]'
                || key === 'CheckoutForm[phone]'
                || key === 'CheckoutForm[email]'
                //fields from selected payment
                || selectedPaymentFields.indexOf( key ) > -1
                || key === 'CheckoutForm[non_us_confirmation]' && CanadaCODs.isActive()
            ) {
                validationFields[ key ] = this.fields[ key ];
            }
        }

        //purchase order details form without purchase order selected

        this.onAfterValidatingFields( validationFields );

        return validationFields;
    }
}
