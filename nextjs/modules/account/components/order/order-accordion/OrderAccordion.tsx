import React from "react";
import { ProblemWithOrder } from "@modules/account/components/orders/ProblemWithOrder";
import { Accordion } from "react-bootstrap";
import { ReturnOrReplaceItems } from "@modules/account/components/orders/ReturnOrReplaceItems";
import { CancelItems } from "@modules/account/components/orders/CancelItems";
import { OrderView } from "@modules/account/ts/types/order/order-view.types";

import Styles from "@modules/account/components/order/order-accordion/OrderAccordion.module.scss";

const plus = (
  <svg
    width="13"
    height="3"
    viewBox="0 0 13 3"
    fill="none"
    xmlns="http://www.w3.org/2000/svg"
  >
    <rect y="0.570312" width="13" height="1.85714" fill="black" />
  </svg>
);

const minus = (
  <svg
    width="13"
    height="13"
    viewBox="0 0 13 13"
    fill="none"
    xmlns="http://www.w3.org/2000/svg"
  >
    <rect y="5.57031" width="13" height="1.85714" fill="black" />
    <rect
      x="5.57129"
      y="13"
      width="13"
      height="1.85714"
      transform="rotate(-90 5.57129 13)"
      fill="black"
    />
  </svg>
);

interface IProps {
  orderItem: OrderView;
}

const OrderAccordion: React.FC<IProps> = (props) => {
  const { orderItem } = props;
  const [activeKey, setActiveKey] = React.useState<string | undefined>(
    undefined
  );

  const changeAccordion = (key: string | undefined) => {
    if (activeKey === key) {
      setActiveKey(undefined);
    } else {
      setActiveKey(key);
    }
  };
  return (
    <Accordion className={Styles.page__accordion} activeKey={activeKey}>
      <div
        onClick={() => changeAccordion("problem")}
        className={`order-actions-accordion-header ${
          activeKey === "problem" && "order-actions-accordion-header__open"
        }`}
      >
        <div>Problem with order</div>
        {activeKey !== "problem" ? minus : plus}
      </div>
      <Accordion.Collapse eventKey="problem">
        <ProblemWithOrder />
      </Accordion.Collapse>
      <div
        onClick={() => changeAccordion("return")}
        className={`order-actions-accordion-header ${
          activeKey === "return" && "order-actions-accordion-header__open"
        }`}
      >
        <div>Return or replace items</div>
        {activeKey !== "return" ? minus : plus}
      </div>
      <Accordion.Collapse eventKey="return">
        <ReturnOrReplaceItems orderItem={orderItem} />
      </Accordion.Collapse>
      <div
        onClick={() => changeAccordion("cancel")}
        className={`order-actions-accordion-header ${
          activeKey === "cancel" && "order-actions-accordion-header__open"
        }`}
      >
        <div>Cancel items</div>
        {activeKey !== "cancel" ? minus : plus}
      </div>
      <Accordion.Collapse eventKey="cancel">
        <CancelItems orderItem={orderItem} />
      </Accordion.Collapse>
    </Accordion>
  );
};

export default OrderAccordion;
