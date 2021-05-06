import classnames from 'classnames';

export default class QuantityGroup extends Component {
    constructor( props ) {
        super( props );
    }

    render( props ) {
        const product = props.product;
        const min = product.min_amount;
        const quantity = min;
        const step = product.mult_order_quantity === 'Y' ? min : 1;

        const classes = props.classes || {};
        const groupClass = classnames( 'quantity-group', classes.group );
        const btnClass = [ 'quantity-group-btn', classes.button ];
        const decClass = classnames( [ btnClass, 'quantity-group-btn_dec', { 'quantity-group-btn_active': quantity > min } ] );
        const incClass = classnames( [ btnClass, 'quantity-group-btn_inc', { 'quantity-group-btn_active': quantity < product.avail } ] );

        return (
            <div className={ groupClass }>
                <span className={ decClass }>
                    <svg className="icon quantity-group-icon"><use xlinkHref="/static/frontend/images/icons/sprite.svg#switcher-minus" /></svg>
                </span>
                <input
                    className="quantity-group-input"
                    type="number"
                    name="quantity"
                    min={ min }
                    max={ product.avail }
                    data-min={ min }
                    step={ step }
                    value={ quantity }
                    id={ "quantity-" + product.productid }
                    autoComplete="off"
                    inputMode="numeric"
                    defaultValue={ quantity }
                />
                <span className={ incClass }>
                    <svg className="icon quantity-group-icon"><use xlinkHref="/static/frontend/images/icons/sprite.svg#switcher-plus" /></svg>
                </span>
            </div>
        );
    }
}
