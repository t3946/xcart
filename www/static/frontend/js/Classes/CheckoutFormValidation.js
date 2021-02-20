import FormValidation from "Classes/FormValidation";
import { ShippingForm } from "../Components/checkout/ShippingForm";
import { BillingForm } from "../Components/checkout/BillingForm";

export default class CheckoutFormValidation extends FormValidation {
    constructor( name ) {
        super( name );
    }

    // show wrong fields if it is hide
    onBeforeFieldShowError( field ) {
        const fieldName = field.element.name;

        // shipping address
        if (
            [
                'CheckoutForm[s_country]',
                'CheckoutForm[s_zipcode]',
                'CheckoutForm[s_state]',
                'CheckoutForm[s_city]',
            ].indexOf( fieldName ) > -1
        ) {
            ShippingForm.showFields();
        }

        // billing address
        if (
            [
                'CheckoutForm[b_country]',
                'CheckoutForm[b_zipcode]',
                'CheckoutForm[b_state]',
                'CheckoutForm[b_city]',
            ].indexOf( fieldName ) > -1
        ) {
            BillingForm.showFields();
        }
    }

    getValidatingFields() {
        const self = this;
        const paymentMethodInputValue = $( this.fields.payment_method.element ).parents( 'form' )[ 0 ][ 'payment_method' ].value;
        const paymentMethodInput = $( `input[name="payment_method"][value="${ paymentMethodInputValue }"` );
        const paymentMethodItem = paymentMethodInput.parents( '.payment-method-item' );
        const selectedPaymentFields = [];

        paymentMethodItem.find( 'input' ).each( function ( i, e ) {
            // shipping address without billing same shipping mode
            if ( e.name.indexOf( '[b_' ) > -1 && self.fields[ 'billing_same_shipping' ].element.checked === false ) {
                return;
            }

            if (
                e.name !== 'payment_method'
            ) {
                selectedPaymentFields.push( e.name );
            }
        } );

        const validationFields = {};

        for ( let key in this.fields ) {
            if ( !this.fields.hasOwnProperty( key ) ) {
                continue;
            }

            this.fields[ key ].removeError();

            if (
                //contact information fields
                key.indexOf( '[ci_' ) > -1
                //shipping fields
                || key.indexOf( '[s_' ) > -1
                //fields from selected payment
                || selectedPaymentFields.indexOf( key ) > -1
            ) {
                validationFields[ key ] = this.fields[ key ];
            }
        }


        return validationFields;
    }
}
