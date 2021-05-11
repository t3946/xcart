import { render, createRef }   from 'preact';
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

        $( document ).on('update.total.checkout', () => {
            const $stripeTarget = $('.stripe-target');
            const options = {
                id: $stripeTarget.data('id'),
                pi: $stripeTarget.data('pi'),
                public_key: $stripeTarget.data('public_key'),
            };

            if ($stripeTarget.length){
                render(<PayByCardStripe { ...options } ref={ this.PayByCardStripe }/>, $stripeTarget[0]);
            }
        });
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

    insertStripeField() {
        //insert stripe
        const $stripeTarget = $( '.stripe-target' );
        const $stripeField = $( '.checkout-stripe' );

        if ($stripeTarget.length) {
            $stripeTarget.append( $stripeField.show() );
        } else {
            $stripeField.hide();
        }

        $( document.forms.CheckoutForm9 ).on( 'beforeCheckoutSubmit', () => this.checkoutSubmit() );
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
        this.insertStripeField();
    }

    componentDidUpdate( previousProps, previousState, previousContext ) {
        this.insertStripeField();
    }

    checkoutSubmit() {
        this.PayByCardStripe.current.sendStripeRequest();
    }

    render() {
        const $stripeTarget = $('.stripe-target');
        const stripeField = {
            id: $stripeTarget.data('id'),
            pi: $stripeTarget.data('pi'),
            public_key: $stripeTarget.data('public_key'),
        };

        return (
            <div className="checkout">
                { stripeField &&
                <PayByCardStripe { ...stripeField } ref={ this.PayByCardStripe }/>
                }
            </div>
        );
    }
}
