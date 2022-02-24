import React from "react";
import cn from "classnames";

import Styles from "@modules/account/components/orders/Decision/IncreaseInShippingCharge/GrandTotalProductOrdered.module.scss";

interface IProps {
  className?: any;
  totals?: {};
  isDecision?: boolean;
}

const GrandTotalProductOrdered: React.FC<IProps> = (props) => {
  const { totals, className, order, isDecision = false } = props;

  function printTaxes() {
    const taxes = [];

    for (const taxesKey in order.taxes) {
      taxes.push(
        <span className={cn([Styles.totalTableTax, Styles.totalTable__tax])}>
          Total {taxesKey}:
        </span>
      );

      taxes.push(
        <span className={cn([Styles.totalTableTax, Styles.totalTable__tax])}>
          US$ {order.taxes[taxesKey].toFixed(2)}
        </span>
      );
    }

    return taxes;
  }

  return (
    <div className={cn([Styles.container, className])}>
      <span>Total items cost:</span>
      <span>US$ {order.subtotal}</span>
      <span
        className={cn(
          Styles.totalTableShippingCost,
          Styles.totalTable__shippingCost,
          {
            [Styles.totalTableShippingCost_highlite]: isDecision,
            [Styles.totalTableShippingCostLabel_highlite]: isDecision,
          }
        )}
      >
        Total shipping cost:
      </span>

      <span
        className={cn(
          Styles.totalTableShippingCost,
          Styles.totalTable__shippingCost,
          {
            [Styles.totalTableShippingCost_highlite]: isDecision,
            [Styles.totalTableShippingCostValue_highlite]: isDecision,
          }
        )}
      >
        US$ 11.90
      </span>

      {printTaxes()}

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
        US$ {order.total}
      </span>
    </div>
  );
};

export default GrandTotalProductOrdered;
