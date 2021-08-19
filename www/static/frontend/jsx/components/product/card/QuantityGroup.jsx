import { createRef } from "preact";
import classnames from "classnames";
import merge from "lodash/merge";

export default class QuantityGroup extends Component {
  constructor(props) {
    super(props);

    const min = parseInt(props.product.min_amount);

    this.inputRef = createRef();

    this.state = {
      min,
      value: min,
      max: parseInt(props.product.avail),
      step: props.product.mult_order_quantity === "Y" ? min : 1,
    };
  }

  sendToUpdate() {
    const value = this.state.value;
    const productid = this.props.product.productid;

    $(document).trigger("component.quantity.change", {
      target: e.target,
      val: value,
      params: params,
      product: $(`[data-product=${productid}]`)[0],
    });
  }

  inc(event) {
    event.stopPropagation();

    const newValue = this.state.value + this.state.step;

    if (newValue <= this.state.max) {
      this.setState({
        value: newValue,
      });

      this.props.onChange(newValue);
    }
  }

  dec(event) {
    event.stopPropagation();

    const newValue = this.state.value - this.state.step;

    if (newValue >= this.state.min) {
      this.setState({
        value: newValue,
      });

      this.props.onChange(newValue);
    }
  }

  componentDidMount() {
    this.props.onChange(this.state.value);

    const $input = $(this.inputRef.current);

    $input.change(() => {
      this.setState({
        value: $input.val(),
      });

      this.props.onChange($input.val());
    });
  }

  render(props) {
    const minBorder = this.state.value === this.state.min;
    const maxBorder = this.state.value === this.state.max;
    const incIconId = minBorder ? "switcher-minus__ash" : "switcher-minus";
    const decIconId = maxBorder ? "switcher-plus__ash" : "switcher-plus";

    const classes = merge(props.classes, {
      group: ["quantity-group"],
      dec: [
        "quantity-group-btn",
        "quantity-group-btn_dec",
        { "quantity-group-btn_active": !minBorder },
      ],
      inc: [
        "quantity-group-btn",
        "quantity-group-btn_inc",
        { "quantity-group-btn_active": !maxBorder },
      ],
    });

    return (
      <div className={classnames(classes.group)}>
        {/*dec button*/}
        <span className={classnames(classes.dec)} onClick={this.dec.bind(this)}>
          <svg className="icon quantity-group-icon">
            <use
              xlinkHref={`/static/frontend/images/icons/sprite.svg#${incIconId}`}
            />
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
          value={this.state.value}
          id={"quantity-" + props.product.productid}
          autoComplete="off"
          inputMode="numeric"
          defaultValue={this.state.min}
          ref={this.inputRef}
        />

        {/*inc button*/}
        <span className={classnames(classes.inc)} onClick={this.inc.bind(this)}>
          <svg className="icon quantity-group-icon">
            <use
              xlinkHref={`/static/frontend/images/icons/sprite.svg#${decIconId}`}
            />
          </svg>
        </span>
      </div>
    );
  }
}
