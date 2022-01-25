import React from "react";
import cn from "classnames";

import Styles from "@modules/account/components/orders/Decision/IncreaseInShippingCharge/GrandTotalProductOrdered.module.scss";

interface IProps {
  className?: any;
  totals?: {};
}

const GrandTotalProductOrdered: React.FC<IProps> = ({ totals, className }) => {
  return (
    <div className={cn([Styles.container, className])}>
      <span>Total items cost:</span>
      <span>US$ 5.70</span>
      <span
        className={cn([
          Styles.totalTableShippingCost,
          Styles.totalTable__shippingCost,
          Styles.totalTableShippingCost__label,
        ])}
      >
        Total shipping cost:
      </span>

      <span
        className={cn([
          Styles.totalTableShippingCost,
          Styles.totalTable__shippingCost,
          Styles.totalTableShippingCost__value,
        ])}
      >
        US$ 11.90
      </span>

      <span className={cn([Styles.totalTableTax, Styles.totalTable__tax])}>
        Total sales tax:
      </span>

      <span className={cn([Styles.totalTableTax, Styles.totalTable__tax])}>
        US$ 1.80
      </span>

      <span className={cn([Styles.totalTableTax])}>Total VAT tax:</span>
      <span className={cn([Styles.totalTableTax])}>US$ 1.80</span>
      <span
        className={cn([
          Styles.totalTableGrandTotal,
          Styles.totalTable__grandTotal,
        ])}
      >
        Grand total:
      </span>

      <span
        className={cn([
          Styles.totalTableGrandTotal,
          Styles.totalTable__grandTotal,
        ])}
      >
        US$ 17.60
      </span>
    </div>
  );
};

export default GrandTotalProductOrdered;
