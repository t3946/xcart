import FormValidation from "@/js/Classes/FormValidation";
import { ShippingForm } from "@/js/Components/checkout/ShippingForm";
import { BillingForm } from "@/js/Components/checkout/BillingForm";
import { CanadaCODs } from "@/js/Components/checkout/CanadaCODs";

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

    /**
     * get fields that must be validated
     * @return {{}}
     */
    getValidatingFields() {
        const self = this;
        const $paymentMethodInput = $(this.fields['CheckoutForm[paymentid]'].element);
        const $paymentMethodItem = $paymentMethodInput.parents( '.payment-method-item' );
        const selectedPaymentFields = [];

        $paymentMethodItem.find( 'input' ).each( function ( i, e ) {
            // shipping address without "billing same shipping" mode
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
                || key === 'CheckoutForm[ci_canada_email_confirmation]' && CanadaCODs.isActive()
            ) {
                validationFields[ key ] = this.fields[ key ];
            }
        }

        this.onAfterValidatingFields( validationFields );

        return validationFields;
    }
}
