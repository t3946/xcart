import React from "react";
import cn from "classnames";

interface IProps {
  classes?: {
    container?: any;
    symbol?: any;
    number?: any;
  };
  currency: Record<any, any>;
  price?: number;
  prices?: Record<any, any>[];
  quantity?: number;
}

export const Price: React.FC<IProps> = function (props) {
  const { classes, currency, price, prices, quantity = 1 } = props;

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

  function formatPrice(price: number) {
    const priceStr = price.toFixed(currency.decimals);

    return priceStr.replace(
      /\B(?=(\d{3})+(?!\d))/g,
      currency.thousands_separator
    );
  }

  function numberTemplate() {
    let priceFormatted = null;

    if (prices && quantity) {
      for (const priceData of prices) {
        if (priceData.quantity <= quantity) {
          priceFormatted = formatPrice(parseFloat(priceData.price));
        }
      }
    } else if (price) {
      priceFormatted = formatPrice(price);
    }

    return <span className={cn(classes?.number)}>{priceFormatted}</span>;
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
