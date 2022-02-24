import React from "react";
import cn from "classnames";

import Styles from "@modules/account/components/order/order-table/ProductCell.module.scss";

interface IProps {
  name: string;
  sku: string;
}

const ProductCell: React.FC<IProps> = ({ name, sku }) => {
  return (
    <span className={cn(["text-left", Styles.name])}>
      <span>{name}</span>
      <br />
      <span className={Styles.sku}>{sku}</span>
    </span>
  );
};

export default ProductCell;
