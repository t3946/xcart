import { SwitcherSlider } from '@/js/Classes/SwitcherSlider';
import BillingForm from '@/js/Components/checkout/BillingForm';
import Checkout    from '@/js/Components/checkout/Checkout';

export const PaymentMethods = ( function () {
    // this component only for checkout page
    if ( document.querySelector( '.checkout-page' ) === null ) {
        return;
    }

    /**
     * created handlers and toggle visibilities
     */
    function init() {
        const $root = $( '.checkout-payment-methods' );
        const selectedClass = 'payment-method-item_selected';
        const $paymentMethods = $root.find( '.payment-method-item' );
        const $radioInputMethods = $root.find( 'input[name="CheckoutForm[paymentid]"]' );
        const $allLongDescriptions = $paymentMethods.find( '.payment-method-description-long' );

        $paymentMethods.click( function ( e ) {
            const $paymentMethodItem = $( this );
            const $input = $paymentMethodItem.find( '[name="CheckoutForm[paymentid]"]' );

            if ( $input.prop( 'checked' ) === false ) {
                $paymentMethods.removeClass( selectedClass );

                $allLongDescriptions
                    .stop( false, true )
                    .slideUp();

                $paymentMethodItem
                    .addClass( selectedClass )
                    .find( '.payment-method-description-long' )
                    .stop( false, true )
                    .slideDown( function () {
                        const elementOffset = $paymentMethodItem.offset().top;
                        const windowScroll = $( 'html' ).scrollTop();

                        // element visible
                        if (
                            elementOffset >= windowScroll
                            && elementOffset < windowScroll + window.innerHeight
                        ) {
                            return;
                        }

                        window.scrollTo( {
                            top: $paymentMethodItem.offset().top,
                            behavior: 'smooth',
                        } );
                    } );
            }

            if ($input.prop( 'checked' ) === false) {
                $input.prop( 'checked', true );
                $input.trigger( 'change' );
            } else {
                $input.prop( 'checked', true );
            }
        } );

        $radioInputMethods
            .filter( ':checked' )
            .parents( '.payment-method-item' )
            .find( '.payment-method-description-long' )
            .show();

        // billing same shipping
        const $addressFields = $( '.billing-form-address-fields' );

        const switcher = new SwitcherSlider(
            $( '.switcher-slider-label' ),
            function () {
                $addressFields.stop( true, false ).slideDown();
                Checkout.update({'CheckoutForm[billing_same_shipping]': '1'});
            },
            function () {
                $addressFields.stop( true, false ).slideUp();
                Checkout.update({'CheckoutForm[billing_same_shipping]': '0'});
            },
        );

        // show fields if billing_same_shipping checked
        if ( switcher.isOn === true ) {
            $addressFields.show();
        }

        // set default select
        const defaultValue = $root.data('default-checked-field');
        let defaultInput = $radioInputMethods.filter(`[value="${defaultValue}"]`) || $radioInputMethods.eq(0);

        // use first as default if no default
        if (defaultInput.length === 0) {
            defaultInput = $radioInputMethods.eq(0);
        }

        defaultInput
            //set active attributes
            .prop('checked', true)
            .parents('.payment-method-item')
            .addClass(selectedClass)
            // show long description
            .find( '.payment-method-description-long' )
            .show();
    }

    const constructor = function () {
        init();
    }

    constructor.prototype.updateTemplate = function ( template ) {
        $( '.payment-methods-container' )[ 0 ].outerHTML = template;
        init();
        BillingForm.updateSwitcher();
    }

    return new constructor();
} )();
