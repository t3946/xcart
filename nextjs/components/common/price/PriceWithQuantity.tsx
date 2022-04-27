import React from "react";
import Price from "@components/common/price/Price";

interface IProps {
  classes?: {
    container?: any;
    symbol?: any;
    number?: any;
  };
  prices: Record<any, any>[];
  quantity: number;
}

export const PriceWithQuantity: React.FC<IProps> = function (props) {
  const { classes, prices, quantity = 1 } = props;

  function getPrice() {
    let price = 0;

    for (const priceData of prices) {
      if (priceData.quantity <= quantity) {
        price = parseFloat(priceData.price);
      }
    }

    return price;
  }

  return <Price price={getPrice()} classes={classes} />;
};

export default PriceWithQuantity;
