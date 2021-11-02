import React, { Fragment } from "react";
import classNames from "classnames";
import { number_format } from "../../../../utils/numberFormat";
interface PriceProduct {
  classes?: any;
  price: number;
}
export const PriceProduct: React.FC<PriceProduct> = ({ classes, price }) => {
  const currency = app.options.currency;
  const formatNumber = () => {
    return number_format(
      price,
      currency.decimal,
      currency.decimals_separator,
      currency.thousands_separator
    );
  };
  return (
    <Fragment>
      {currency.symbol_prefix}
      {!currency.after && `${currency.currency} `}
      <span className={classNames(["price-number", classes])}>
        {formatNumber()}
      </span>
      {currency.after && currency.currency}
    </Fragment>
  );
};
