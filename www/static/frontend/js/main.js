import './Components/checkout/Checkout';
import './Components/checkout/BillingForm';
import './Components/checkout/BillingSameShipping';
import './Components/checkout/DistributorCart';
import './Components/checkout/PaymentMethods';
import './Components/checkout/ShippingForm';
import './Components/checkout/ScrollUpButton';
import './Components/checkout/ShippingMethods';
import './Components/checkout/CanadaCODs';
import './Components/checkout/CheckoutTotal';
import './Classes/AddToCartButton';

export default ( function () {
    let timerThrottle = null;

    $( window ).resize( function ( e ) {
        clearTimeout( timerThrottle );

        timerThrottle = setTimeout( function () {
            const customEvent = new Event( 'window_resize', {
                data: {
                    originalEvent: e,
                },
            } );
            document.dispatchEvent( customEvent );
        }, 300 );
    } );
} )();