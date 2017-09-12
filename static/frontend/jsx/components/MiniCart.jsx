import { h, Component } from 'preact';
// import { Provider, connect } from 'preact-redux';

export default class MiniCart extends Component
{
    constructor(state, props) {
        super();

        this.state = props.store.getState();
        this.unsubscribe = props.store.subscribe(()=>{
            this.setState(props.store.getState());
        });
    }


    componentWillUnmount() {
        this.unsubscribe();
    }

    render() {
        return (
        <div className="minicart-items">
            <div className="product-list">
                Total: {this.state.cart.total}
            </div>
            <div className="buttons">
                <a href="/cart/" className="button yellow waves waves-orange">
                    View cart
                </a>
            </div>
        </div>);
    }
}