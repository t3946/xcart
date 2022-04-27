import React from "react";
import cn from "classnames";
import Styles from "@modules/account/components/orders/Decision/IncreaseInShippingCharge/IncreaseInShippingCharge.module.scss";
import { getTaxesGroup } from "@utils/countTaxesOrder";
import Price from "@components/common/price/Price";

interface IProps {
  paymentStatus: string;
  shippingStatus: string;
  regularShipping: number;
  salesTax: number;
  vatTax: number;
  subtotal: number;
  order: any;
  group: any;
}

const TableFooter: React.FC<IProps> = (props) => {
  const { paymentStatus, shippingStatus, regularShipping, subtotal, group } =
    props;

  function taxesTemplate() {
    const taxes = getTaxesGroup(group);
    const templates = [];
    let i = 0;

    for (const name in taxes) {
      const value = taxes[name];

      templates.push(
        <span
          className={cn([
            Styles.tableFooterShippingSubtotalTax,
            Styles.tableFooterShippingSubtotal__tax,
          ])}
          key={`tax-name-${i}`}
        >
          {name}:
        </span>
      );

      templates.push(
        <span
          className={cn([Styles.tableFooterShippingSubtotalTax])}
          key={`tax-value-${i}`}
        >
          <Price price={value} />
        </span>
      );

      i++;
    }

    return templates;
  }

  return (
    <div
      className={cn([
        "d-flex",
        "flex-dir-column",
        "justify-content-between",
        Styles.tableFooter,
        Styles.table__footer,
      ])}
    >
      <div className={cn([Styles.tableFooterDetails])}>
        <div
          className={cn([
            "mb-md-1",
            "mb-lg-14",
            "d-flex",
            "d-md-block",
            "justify-content-between",
            Styles.tableFooterDetailsLine,
          ])}
        >
          <b className={cn(["me-1", "me-md-20", "me-lg-2"])}>Payment status:</b>{" "}
          {paymentStatus}
        </div>

        <div
          className={cn([
            "d-flex",
            "d-md-block",
            "justify-content-between",
            Styles.tableFooterDetailsLine,
          ])}
        >
          <b className={cn(["me-1", "me-md-20", "me-lg-2"])}>
            Shipping status:
          </b>{" "}
          {shippingStatus}
        </div>
      </div>

      <div className={cn([Styles.tableFooterShippingTotals])}>
        {regularShipping > 0 && (
          <>
            <span
              className={cn([
                Styles.tableFooterShippingSubtotalRegularShipping,
              ])}
            >
              Regular shipping:
            </span>

            <span
              className={cn([
                Styles.tableFooterShippingSubtotalRegularShipping,
                Styles.tableFooterShippingSubtotal__regularShipping,
              ])}
            >
              <Price price={regularShipping} />
            </span>
          </>
        )}

        {taxesTemplate()}

        <span
          className={cn([
            Styles.tableFooterShippingSubtotal,
            Styles.tableFooterShipping__subtotal,
          ])}
        >
          Subtotal:
        </span>

        <span
          className={cn([
            Styles.tableFooterShippingSubtotal,
            Styles.tableFooterShipping__subtotal,
          ])}
        >
          <Price price={subtotal} />
        </span>
      </div>
    </div>
  );
};

export default TableFooter;
