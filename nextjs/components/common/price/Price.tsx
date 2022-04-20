import React from "react";
import cn from "classnames";

interface IProps {
  classes?: {
    container?: any;
    symbol?: any;
    number?: any;
  };
  currency: Record<any, any>;
  price: number;
}

export const Price: React.FC<IProps> = function (props) {
  const { classes, currency, price } = props;

  function prefixTemplate() {
    if (currency.after !== "N") {
      return null;
    }

    return <span className={cn(classes?.symbol)}>{currency.symbol}</span>;
  }

  function postfixTemplate() {
    if (currency.after !== "Y") {
      return null;
    }

    return <span className={cn(classes?.symbol)}>{currency.symbol}</span>;
  }

  function numberWithCommas(x: string) {
    return x.replace(/\B(?=(\d{3})+(?!\d))/g, currency.thousands_separator);
  }

  function numberTemplate() {
    const priceFloat = price.toFixed(currency.decimals);
    const priceStr = numberWithCommas(priceFloat);

    return <span className={cn(classes?.number)}>{priceStr}</span>;
  }

  function symbolPrefixTemplate() {
    if (!currency.symbol_prefix) {
      return null;
    }

    return currency.symbol_prefix + " ";
  }

  return (
    <span className={cn(classes?.container, "ws-nowrap")}>
      {symbolPrefixTemplate()}

      {prefixTemplate()}

      {numberTemplate()}

      {postfixTemplate()}
    </span>
  );
};

export default Price;
