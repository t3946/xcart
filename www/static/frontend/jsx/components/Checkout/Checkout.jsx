export default class Checkout extends Component {
    componentDidMount() {
        //this solution have to smooth No-React to React transition
        //use html permutations and inserts for creating full-react component someday

        //no-react checkout
        const $checkoutElement = $( '.cart_shipping-page.default-content-page' );

        //react container
        const $root = $( '.checkout' );

        // insert no-react-code in react container
        $checkoutElement.appendTo( $root );
    }

    render() {
        return (
            <div className="checkout"></div>
        );
    }
}
