import classnames from "classnames";
import _ from "lodash";

export default class QuantityGroup extends Component {
  constructor(props) {
    super(props);

    const min = parseInt(props.product.min_amount);

    this.state = {
      min,
      quantity: min,
      max: parseInt(props.product.avail),
      step: props.product.mult_order_quantity === "Y" ? min : 1,
    };
  }

  render(props) {
    const classes = _.merge(props.classes, {
      group: ["quantity-group"],
      dec: [
        "quantity-group-btn",
        "quantity-group-btn_dec",
        { "quantity-group-btn_active": this.state.quantity > this.state.min },
      ],
      inc: [
        "quantity-group-btn",
        "quantity-group-btn_inc",
        { "quantity-group-btn_active": this.state.quantity < this.state.max },
      ],
    });

    return (
      <div className={classnames(classes.group)}>
        {/*dec button*/}
        <span className={classnames(classes.dec)}>
          <svg className="icon quantity-group-icon">
            <use xlinkHref="/static/frontend/images/icons/sprite.svg#switcher-minus" />
          </svg>
        </span>

        <input
          className="quantity-group-input"
          type="number"
          name="quantity"
          min={this.state.min}
          max={this.state.max}
          data-min={this.state.min}
          step={this.state.step}
          value={this.state.quantity}
          id={"quantity-" + props.product.productid}
          autoComplete="off"
          inputMode="numeric"
        />

        {/*inc button*/}
        <span className={classnames(classes.inc)}>
          <svg className="icon quantity-group-icon">
            <use xlinkHref="/static/frontend/images/icons/sprite.svg#switcher-plus" />
          </svg>
        </span>
      </div>
    );
  }
}
