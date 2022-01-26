import classNames from "classnames";
import { Fragment } from "react";

function formatNumber(number) {
  return Intl.NumberFormat("en-US", { style: "currency", currency: "USD" })
    .format(number)
    .substr(1);
}

export default function Price(props) {
  const { currency, price, classes } = props;

  return (
    <Fragment>
      {currency.symbol_prefix}
      {!currency.after && currency.currency}
      <span className={classNames(["price-number", classes])}>
        {" "}
        {formatNumber(price)}
      </span>
      {currency.after && currency.currency}
    </Fragment>
  );
}
