import { h, Component } from 'preact';
import _ from 'lodash';
import 'SimpleBar';
// import { Provider, connect } from 'preact-redux';

export default class MiniCart extends Component
{
    constructor(state, props) {
        super();

        this.changes = {};
        this.timers = {};
        this.simplebar = null;

        this.state = props.store.getState();
        this.unsubscribe = props.store.subscribe(()=>{
            this.setState(props.store.getState());
        });
    }

    componentWillUnmount() {
        this.unsubscribe();
    }

    componentDidMount() {
        this.simplebar = new SimpleBar(document.querySelector('.minicart-items .product-list'))
    }

    handleRemove(e, key, item)
    {
        e.preventDefault();

        this.context.store.dispatch({type:'PUSH', action: 'DEL', data: {items:[key]}});
    }

    handleInput(e, key, item)
    {
        let val = e.target.value;

        // e.preventDefault();
        clearTimeout(this.timers.change);

        if (val && val > 0) {
            this.timers.change = setTimeout(()=>{
                this.changes[key] = e.target.value;

                this.context.store.dispatch({
                    type:'PUSH',
                    action: 'SET',
                    data: { items: [{ id:item.id, quantity: e.target.value }] },

                    callback: ()=> {
                        this.changes[key] = null;
                    }
                });
            }, 500);
        }
    }

    renderImage(item)
    {
        if (item.image) {
            return <img data-src={item.image}  alt={item.name} className="lazy lazy-img" itemprop="image" />;
        }

        return (
            <div className="not-avail">
                <span className="text">
                    Image not available
                </span>
            </div>
        );
    }

    renderProducts()
    {
        if (this.state.cart.items) {

            return _.map(this.state.cart.items, (item, key)=> (
                <div className="item" key={key} data-product={item.id}>
                    <div className="image">
                        {this.renderImage(item)}
                    </div>

                    <div className="name-quantity">
                        <div className="name">
                            {item.name}
                        </div>

                        <div className="quantity-extended">
                            <div className="quantity">
                                <input type="number" min="1" max={item.avail} value={this.changes[key] || item.quantity} onInput={(e)=>{ this.handleInput(e, key, item); }}/>
                            </div>
                            <div className="x">
                                x
                            </div>
                            <div className="price">
                                US$ {item.price}
                            </div>
                        </div>
                    </div>

                    <div className="actions">
                        <a href="#" className="icon cart_remove text-hide" onClick={(e)=>{ this.handleRemove(e, key, item); }} title="Remove"></a>
                    </div>
                </div>
            ));
        }

        return;
    }

    render() {
        return (
        <div className="minicart-items">
            <div className="product-list">
                {this.renderProducts()}
            </div>
            <div className="buttons">
                <a href="/cart/" className="button yellow waves waves-orange">
                    View cart
                </a>
            </div>
        </div>);
    }
}