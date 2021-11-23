import React from "react";
import classnames from "classnames";

export enum EType {
  freeShipping = "free-shipping",
  flatShipping = "flat-shipping",
  leadTime = "lead-time",
  multiplyQuantity = "multiply-quantity",
  lastItems = "last-items",
  outOfStock = "out-of-stock",
}

export interface IProps {
  type: EType;
  text: string;
  containerClass?: any;
  iconClass?: any;
}

const Label: React.FC<IProps> = function (props: IProps) {
  const { type, text, containerClass, iconClass } = props;

  const classes = {
    containerClass: ["product-label", `product-label__${type}`, containerClass],
    iconClass: [
      "product-label-icon ",
      `product-label-icon__${type}`,
      iconClass,
    ],
  };

  return (
    <div className={classnames(classes.containerClass)}>
      <i className={classnames(classes.iconClass)} />
      <div className="product-label-text">{text}</div>
    </div>
  );
};

export default Label;
