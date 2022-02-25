import React from "react";
import cn from "classnames";

import Styles from "@modules/account/components/order/order-table/ProductCell.module.scss";

interface IProps {
  name: string;
  sku: string;
  url?: string;
}

const ProductCell: React.FC<IProps> = ({ name, sku, url }) => {
  return (
    <span className={cn(["text-left", Styles.name])}>
      <a href={url}>{name}</a>
      <br />
      <span className={Styles.sku}>{sku}</span>
    </span>
  );
};

export default ProductCell;
