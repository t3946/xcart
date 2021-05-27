import { h, Component, render } from 'preact';
import _ from 'lodash';

class MiniCart extends Component
{
    constructor(props, state) {
        super(props, state);
        this.changes = {};
        this.timers = {};
        this.simplebar = null;
        this.product_list = null;
        this.store = props.store;
        this.state = this.store.getState();
        this.unsubscribe = props.store.subscribe(()=>{
            this.setState(props.store.getState());
        });
    }

    componentWillUnmount() {
        this.unsubscribe();
    }

    handleRemove(e, key, item)
    {
        e.preventDefault();
        this.store.dispatch({type:'PUSH', action: 'DEL', data: {items:[key]}});
    }

    /**
     * save new product quantity
     */
    handleInput(e, key, item)
    {
        let val = e.target.value;

        clearTimeout(this.timers.change);

        if (val && val > 0) {
            this.timers.change = setTimeout(()=>{
                this.changes[key] = e.target.value;

                this.store.dispatch({
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

    renderImage(item, props)
    {
        if (item.image) {
            return <img data-src={item.image}  alt={item.name} className="lazy lazy-img" itemprop="image" />;
        }

        return (
            <div className="not-avail">
                <span className="text">
                    {props.labels.img}
                </span>
            </div>
        );
    }

    renderOptions(options)
    {
        if(options.length <= 0) {
            return;
        }

        return _.map(options, (oneOption) => {

            if(oneOption.type === 'color'){
                let colorStyle = 'background-color:' + oneOption.value + ';';
                return(<span className="product-option">
                    <span className="product-option__title">{oneOption.title}:</span>
                    <span className="product-option__color" style={colorStyle} />
                    <span className="product-option__name">{oneOption.name}</span>
                </span>);
            }

            return(<span className="product-option">
                    <span className="product-option__title">{oneOption.title}:</span>
                    <span className="product-option__name">{oneOption.name}</span>
                </span>);

        });
    }

    renderProducts(props, state)
    {
        if (this.state.cart.items) {

            return _.map(this.state.cart.items, (item, key)=> (
                <div className="item" key={key} data-product={item.id}>
                    <div className="image">
                        {this.renderImage(item, props)}
                    </div>

                    <div className="name-quantity">
                        <div className="name">
                            <a href={item.href}>
                                <span className="name-text">{item.name}</span>
                                <span className="name-options">{this.renderOptions(item.options)}</span>
                            </a>
                        </div>

                        <div className="quantity-extended">
                            <div className="quantity">
                                <input type="number" min="1" max={item.avail} value={this.changes[key] || item.quantity} onInput={(e)=>{ this.handleInput(e, key, item); }}/>
                            </div>
                            <div className="x">
                                x
                            </div>
                            <div className="price">
                                {this.state.cart.currency} {item.price}
                            </div>
                        </div>
                    </div>

                    <div className="actions">
                        <a href="#" className="icon cart_remove" onClick={(e)=>{ this.handleRemove(e, key, item); }} title={props.labels.lng_remove}></a>
                    </div>
                </div>
            ));
        }

        return;
    }

    render(props, state) {
        return (
        <div className="minicart-items">
            <div className="product-list" ref={(product_list) => { this.product_list = product_list; }}>
                {this.renderProducts(props, state)}
            </div>
            <div className="buttons">
                <a href={props.checkoutUrl} className="button yellow waves waves-orange">
                    {props.labels.lng_checkout}
                </a>
            </div>
        </div>);
    }
}

export default MiniCart;