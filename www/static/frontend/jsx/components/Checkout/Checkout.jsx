import { createRef }   from 'preact';
import PayByCardStripe from '@/components/Checkout/PayByCardStripe';

export default class Checkout extends Component {
    constructor() {
        super();
        this.PayByCardStripe = createRef();
        this.checkoutSubmit.bind( this );
        this.state = {
            // need for update total in checkout only after last request
            checkoutUpdateQueries: 0,
        };

        $( document ).on( 'updateRequestSend.checkout', () => {
            this.setState( {
                checkoutUpdateQueries: this.state.checkoutUpdateQueries + 1,
            } );

            $('.order-total_preloader').fadeIn();
        } );

        $( document ).on( 'updateRequestSuccess.checkout', ( e, total ) => {
            if ( this.state.checkoutUpdateQueries === 1 ) {
                this.updateTotal( total );
            }
        } );

        $( document ).on( 'updateRequestComplete.checkout', () => {
            const checkoutUpdateQueries = this.state.checkoutUpdateQueries - 1;

            this.setState( { checkoutUpdateQueries, } );

            //hide preloader if this is the last query
            if ( checkoutUpdateQueries === 0 ) {
                $( '.order-total_preloader' ).fadeOut();
            }
        } );
    }

    formatNumber( number ) {
        return Intl
            .NumberFormat( 'en-US', { style: 'currency', currency: 'USD' } )
            .format( number )
            .substr( 1 );
    }

    updateTotal( total ) {
        $( '.order-total .total .price' ).text( this.formatNumber( total[ 'total' ] ) );
        $( '.shipping-total .price' ).text( this.formatNumber( total[ 'total_shipping_cost' ] ) );
        $( '.total-sales-tax .price' ).text( this.formatNumber( total[ 'total_sales_tax' ] ) );
        $( '.total-vat-tax .price' ).text( this.formatNumber( total[ 'total_vat_tax' ] ) );
        $( '.grand-total .price' ).text( this.formatNumber( total[ 'grand_total' ] ) );
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
        let stripeField;

        if ( app.options.payByCardForm ) {
            stripeField = app.options.payByCardForm.stripeField;
        }

        return (
            <div className="checkout">
                { stripeField &&
                <PayByCardStripe { ...stripeField } ref={ this.PayByCardStripe }/>
                }
            </div>
        );
    }
}
