import React from "react";
import cn from "classnames";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";

interface IProps {
  classes?: {
    container?: any;
    symbol?: any;
    number?: any;
  };
  price: number | string;
}

export const Price: React.FC<IProps> = function (props) {
  const { classes, price } = props;
  const currency = useSelectorAccount((e) => e.config.site.currency);

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

  function formatPrice(price: number | string) {
    if (typeof price === "string") {
      price = parseFloat(price);
    }

    const priceStr = price.toFixed(currency.decimals);

    return priceStr.replace(
      /\B(?=(\d{3})+(?!\d))/g,
      currency.thousands_separator
    );
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

      <span className={cn(classes?.number)}>{formatPrice(price)}</span>

      {postfixTemplate()}
    </span>
  );
};

export default Price;
