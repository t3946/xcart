import classNames from "classnames";
import { Fragment } from "preact";

export default function Price(props) {
  const { currency, price, classes } = props;

  return (
    <Fragment>
      {currency.symbol_prefix}
      {!currency.after && currency.currency}
      <span className={classNames(["price-number", classes])}> {price}</span>
      {currency.after && currency.currency}
    </Fragment>
  );
}
