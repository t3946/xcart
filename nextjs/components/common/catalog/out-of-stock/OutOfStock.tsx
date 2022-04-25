import * as React from "react";
import Styles from "@components/common/catalog/out-of-stock/OutOfStock.module.scss";
import IconOutOfStock from "@components/common/icons/out-of-stock/OutOfStock";
import cn from "classnames";

export const OutOfStock: React.FC = function () {
  return (
    <div className={cn(Styles.IconOutOfStock, "d-flex", "align-items-center")}>
      <IconOutOfStock className={Styles.icon} />
      <span className={cn(Styles.text, "ms-2")}>Out of stock</span>
    </div>
  );
};

export default OutOfStock;
