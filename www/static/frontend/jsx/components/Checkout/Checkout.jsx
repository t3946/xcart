import { createRef }   from 'preact';
import PayByCardStripe from '@/components/Checkout/PayByCardStripe';

export default class Checkout extends Component {
    constructor() {
        super();
        this.PayByCardStripe = createRef();
        this.checkoutSubmit.bind( this );
    }

    componentDidMount() {
        //this solution have to smooth No-React to React transition
        //use html permutations and inserts for creating full-react component someday

        //no-react checkout
        const $checkoutElement = $( '.cart_shipping-page.default-content-page' );

        //react container
        const $root = $( '.checkout' );

        // insert no-react-code in react container
        $checkoutElement.appendTo( $root );

        //insert stripe
        const $stripeTarget = $( '.stripe-target' );
        const $stripeField = $( '.checkout-stripe' );
        $stripeTarget.append( $stripeField );

        $( document.forms.CheckoutForm9 ).on( 'beforeCheckoutSubmit', () => this.checkoutSubmit() );
    }

    checkoutSubmit() {
        this.PayByCardStripe.current.sendStripeRequest();
    }

    render() {
        const stripeField = app.options.payByCardForm.stripeField;

        return (
            <div className="checkout">
                <PayByCardStripe { ...stripeField } ref={ this.PayByCardStripe }/>
            </div>
        );
    }
}
