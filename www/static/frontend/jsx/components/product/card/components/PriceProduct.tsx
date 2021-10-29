import React, { Fragment } from "react";
import classNames from "classnames";
interface PriceProduct {
  classes?: any;
  price: number;
}
export const PriceProduct: React.FC<PriceProduct> = ({ classes, price }) => {
  const currency = app.options.currency;
  const formatNumber = () => {
    switch (currency.currency_code) {
      case "RUB":
        return (
          price
            .toFixed(currency.decimal)
            .toString()
            .replace(
              /(\d{1,3}(?=(?:\d\d\d)+(?!\d)))/g,
              "$1" + currency.thousands_separator
            ) + " "
        );
      default:
        return Intl.NumberFormat("en-US", {
          style: "currency",
          currency: "USD",
        })
          .format(price)
          .substr(1);
    }
  };
  return (
    <Fragment>
      {currency.symbol_prefix}
      {!currency.after && `${currency.currency} `}
      <span className={classNames(["price-number", classes])}>
        {formatNumber()}
      </span>
      {currency.currency}
    </Fragment>
  );
};
