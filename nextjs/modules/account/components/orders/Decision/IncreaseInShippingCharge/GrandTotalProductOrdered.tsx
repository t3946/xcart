import React from "react";
import cn from "classnames";
import { countTaxesOrder } from "@utils/countTaxesOrder";
import Styles from "@modules/account/components/orders/Decision/IncreaseInShippingCharge/GrandTotalProductOrdered.module.scss";
import Price from "@components/common/price/Price";

interface IProps {
  className?: any;
  totals?: any;
  isDecision?: boolean;
  order: any;
  totalShippingInFrame?: boolean;
}

const GrandTotalProductOrdered: React.FC<IProps> = (props) => {
  const { className, order, totalShippingInFrame = false } = props;

  function printTaxes() {
    const taxes = countTaxesOrder(order);
    const templates = [];
    let i = 0;

    for (const name in taxes) {
      const value = taxes[name];

      templates.push(
        <span
          key={`tax-name-${i}`}
          className={cn([Styles.totalTableTax, Styles.totalTable__tax])}
        >
          Total {name}:
        </span>
      );

      templates.push(
        <span
          key={`tax-value-${i}`}
          className={cn([Styles.totalTableTax, Styles.totalTable__tax])}
        >
          <Price price={value} />
        </span>
      );

      i++;
    }

    return templates;
  }

  const shippingTotal = parseFloat(order.shipping_cost);

  return (
    <div className={cn([Styles.container, className])}>
      <span>Total items cost:</span>
      <Price price={order.subtotal} />
      {shippingTotal > 0 && (
        <>
          <span
            className={cn(
              Styles.totalTableShippingCost,
              Styles.totalTable__shippingCost,

              {
                [Styles.totalTableShippingCost_highlight]: totalShippingInFrame,
                [Styles.totalTableShippingCostLabel_highlight]:
                  totalShippingInFrame,
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
                [Styles.totalTableShippingCost_highlight]: totalShippingInFrame,
                [Styles.totalTableShippingCostValue_highlight]:
                  totalShippingInFrame,
              }
            )}
          >
            <Price price={shippingTotal} />
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
        <Price price={order.total} />
      </span>
    </div>
  );
};

export default GrandTotalProductOrdered;
