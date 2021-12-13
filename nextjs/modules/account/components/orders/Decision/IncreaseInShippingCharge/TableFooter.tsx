import React from "react";
import cn from "classnames";
import Styles from "@modules/account/components/orders/Decision/IncreaseInShippingCharge/IncreaseInShippingCharge.module.scss";

interface IProps {
  paymentStatus: string;
  shippingStatus: string;
  regularShipping: number;
  salesTax: number;
  vatTax: number;
  subtotal: number;
}

const TableFooter: React.FC<IProps> = ({
  paymentStatus,
  shippingStatus,
  regularShipping,
  salesTax,
  vatTax,
  subtotal,
}) => {
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
        <span
          className={cn([Styles.tableFooterShippingSubtotalRegularShipping])}
        >
          Regular shipping:
        </span>

        <span
          className={cn([
            Styles.tableFooterShippingSubtotalRegularShipping,
            Styles.tableFooterShippingSubtotal__regularShipping,
          ])}
        >
          US$ {regularShipping.toFixed(2)}
        </span>

        <span
          className={cn([
            Styles.tableFooterShippingSubtotalTax,
            Styles.tableFooterShippingSubtotal__tax,
          ])}
        >
          Sales Tax:
        </span>

        <span className={cn([Styles.tableFooterShippingSubtotalTax])}>
          US$ {salesTax.toFixed(2)}
        </span>

        <span className={cn([Styles.tableFooterShippingSubtotalTax])}>
          VAT Tax:
        </span>

        <span className={cn([Styles.tableFooterShippingSubtotalTax])}>
          US$ {vatTax.toFixed(2)}
        </span>

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
          US$ {subtotal.toFixed(2)}
        </span>
      </div>
    </div>
  );
};

export default TableFooter;
