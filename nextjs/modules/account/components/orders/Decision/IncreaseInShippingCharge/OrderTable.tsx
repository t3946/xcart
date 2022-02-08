import React from "react";
import { Accordion } from "react-bootstrap";
import Plus from "@modules/icon/components/font-awesome/plus/Regular";
import Minus from "@modules/icon/components/font-awesome/minus/Regular";
import ChevronDown from "@modules/icon/components/font-awesome/chevron-down/Regular";
import cn from "classnames";
import ShippingTable from "@modules/account/components/orders/Decision/IncreaseInShippingCharge/ShippingTable";
import GrandTotalProductOrdered from "@modules/account/components/orders/Decision/IncreaseInShippingCharge/GrandTotalProductOrdered";

import Styles from "@modules/account/components/orders/Decision/IncreaseInShippingCharge/IncreaseInShippingCharge.module.scss";

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
          {order.groups.map((group) => (
            <ShippingTable group={group} />
          ))}
        </div>
      </Accordion.Collapse>
      <GrandTotalProductOrdered
        className={{
          "border-0": !isOpened,
          [Styles.accordionFooter_opened]: isOpened,
        }}
        order={order}
      />
    </Accordion>
  );
};

export default OrderTable;
