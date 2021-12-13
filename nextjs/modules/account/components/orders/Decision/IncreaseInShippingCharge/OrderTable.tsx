import React from "react";
import {
  Accordion,
  AccordionContext,
  useAccordionButton,
} from "react-bootstrap";
import Plus from "@modules/icon/components/font-awesome/plus/Regular";
import Minus from "@modules/icon/components/font-awesome/minus/Regular";
import ChevronDown from "@modules/icon/components/font-awesome/chevron-down/Regular";

import Styles from "@modules/account/components/orders/Decision/IncreaseInShippingCharge/IncreaseInShippingCharge.module.scss";
import Table, {
  TableTypes,
} from "@modules/account/components/orders/Decision/Table";
import TableFooter from "@modules/account/components/orders/Decision/IncreaseInShippingCharge/TableFooter";
import cn from "classnames";

interface IProps {
  order: {
    city: string;
    state: string;
    country: string;
    regularShipping: number;
    salesTax: number;
    vatTax: number;
    subtotal: number;
    paymentStatus: string;
    shippingStatus: string;
    items: {
      name: string;
      sku: string;
      price: number;
      amount: number;
      total?: number;
      image: string;
    }[];
  }[];
}

const OrderTable: React.FC<IProps> = (props: IProps) => {
  const { order } = props;

  const [opened, setOpened] = React.useState("");

  const isOpened = opened === "true";
  function accordionButtonTemplate() {
    return (
      <button
        type="button"
        className={cn(["w-100", Styles.accordionHeader])}
        onClick={() => setOpened((prevstate) => (prevstate ? "" : "true"))}
      >
        Products ordered
        <div
          className={cn([
            "position-relative",
            "d-flex",
            "d-md-none",
            "d-lg-flex",
          ])}
        >
          <Plus
            className={cn([
              Styles.accordionIcon,
              { [Styles.accordionIcon_hidden]: isOpened },
            ])}
          />

          <Minus
            className={cn([
              Styles.accordionIcon,
              Styles.accordion__icon,
              { [Styles.accordionIcon_hidden]: !isOpened },
            ])}
          />
        </div>
        <div
          className={cn([
            "position-relative",
            "d-none",
            "d-md-flex",
            "d-lg-none",
          ])}
        >
          <ChevronDown
            className={cn([
              Styles.accordionIcon,
              Styles.accordionIcon_chevron,
              { [Styles.accordionIcon_chevron_rotate]: isOpened },
            ])}
          />
        </div>
      </button>
    );
  }
  return (
    <Accordion activeKey={opened} className={Styles.accordion}>
      {accordionButtonTemplate()}
      <Accordion.Collapse eventKey="true">
        <div>
          {order.map((shipping) => {
            const items = shipping.items.map((item) => {
              const total = parseFloat((item.price * item.amount).toFixed(2));
              return { ...item, total };
            });
            return (
              <>
                <div className={cn([Styles.table__name, Styles.tableName])}>
                  The items below are shipped from {shipping.city},{" "}
                  {shipping.state}, {shipping.country}
                </div>

                <Table
                  tableType={TableTypes.increaseInShippingCharge}
                  items={items}
                />
                <TableFooter
                  paymentStatus={shipping.paymentStatus}
                  shippingStatus={shipping.shippingStatus}
                  regularShipping={shipping.regularShipping}
                  salesTax={shipping.salesTax}
                  vatTax={shipping.vatTax}
                  subtotal={shipping.subtotal}
                />
              </>
            );
          })}
        </div>
      </Accordion.Collapse>
      <div
        className={cn([
          Styles.accordionFooter,
          {
            [Styles.accordionFooter_border]: isOpened,
            [Styles.accordionFooter_opened]: isOpened,
          },
        ])}
      >
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
    </Accordion>
  );
};

export default OrderTable;
