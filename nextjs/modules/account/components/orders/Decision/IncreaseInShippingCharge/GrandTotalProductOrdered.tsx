import React from "react";
import cn from "classnames";
import {countTaxesOrder} from "@utils/countTaxesOrder";
import Styles
  from "@modules/account/components/orders/Decision/IncreaseInShippingCharge/GrandTotalProductOrdered.module.scss";

interface IProps {
  className?: any;
  totals?: any;
  isDecision?: boolean;
  order: any;
}

const GrandTotalProductOrdered: React.FC<IProps> = (props) => {
  const { className, order } = props;

  function printTaxes() {
    const taxes = countTaxesOrder(order);
    const templates = [];

    for (const name in taxes) {
      const value = taxes[name];

      templates.push(
        <span className={cn([Styles.totalTableTax, Styles.totalTable__tax])}>
          Total {name}:
        </span>
      );

      templates.push(
        <span className={cn([Styles.totalTableTax, Styles.totalTable__tax])}>
          US$ {value.toFixed(2)}
        </span>
      );
    }

    return templates;
  }

  const shippingTotal = parseFloat(order.shipping_cost);

  return (
    <div className={cn([Styles.container, className])}>
      <span>Total items cost:</span>
      <span>US$ {order.subtotal}</span>
      {shippingTotal > 0 && (
        <>
          <span
            className={cn(
              Styles.totalTableShippingCost,
              Styles.totalTable__shippingCost,
              Styles.totalTableShippingCost_highlight,
              Styles.totalTableShippingCostLabel_highlight
            )}
          >
            Total shipping cost:
          </span>

          <span
            className={cn(
              Styles.totalTableShippingCost,
              Styles.totalTable__shippingCost,
              Styles.totalTableShippingCost_highlight,
              Styles.totalTableShippingCostValue_highlight
            )}
          >
            US$ {shippingTotal.toFixed(2)}
          </span>
        </>
      )}

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
