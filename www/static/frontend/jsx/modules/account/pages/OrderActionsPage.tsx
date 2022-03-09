import React, { useEffect } from "react";
import { Tab, Tabs } from "react-bootstrap";
import { CancelItems } from "@client/modules/account/components/orders/CancelItems";
import { ReturnOrReplaceItems } from "@client/modules/account/components/orders/ReturnOrReplaceItems";
import { ProblemWithOrder } from "@client/modules/account/components/orders/ProblemWithOrder";
import { useAccordion } from "@client/modules/account/hooks/useAccordion";
import { getBreakpointsFlags } from "@client/modules/account/hooks/useBreakpoint";
import Store from "@client/jsx/redux/stores/Store";
import { setBreakpoint } from "@client/jsx/redux/actions/account-actions/MainActions";
import { useSelector } from "react-redux";
import { AccountStore } from "@client/modules/account/ts/types/store.type";

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

interface OrderActionsPageProps {
  orderItem?: any;
}

export const OrderActionsPage: React.FC<OrderActionsPageProps> = ({
  orderItem,
}) => {
  useEffect(() => {
    Store.dispatch(setBreakpoint(getBreakpointsFlags(window.innerWidth)));
  }, []);
  const breakpoint = useSelector((e: AccountStore) => e.main.breakpoint);

  console.log(breakpoint);

  function showAccordions() {
    const problemAccordion = useAccordion();
    const returnAccordion = useAccordion();
    const cancelAccordion = useAccordion();
    return (
      <div>
        <div
          onClick={problemAccordion.onItemClick}
          className={`order-actions-accordion-header ${
            problemAccordion.open && "order-actions-accordion-header__open"
          }`}
        >
          <div>Problem with order</div>
          {!problemAccordion.open ? minus : plus}
        </div>
        <div
          className={"order-actions-accordion-body"}
          style={{
            height: problemAccordion.height,
          }}
          ref={problemAccordion.ref}
        >
          <ProblemWithOrder />
        </div>
        <div
          onClick={returnAccordion.onItemClick}
          className={`order-actions-accordion-header ${
            returnAccordion.open && "order-actions-accordion-header__open"
          }`}
        >
          <div>Return or replace items</div>
          {!returnAccordion.open ? minus : plus}
        </div>
        <div
          className={"order-actions-accordion-body"}
          style={{
            height: returnAccordion.height,
          }}
          ref={returnAccordion.ref}
        >
          <ReturnOrReplaceItems orderItem={orderItem} />
        </div>
        <div
          onClick={cancelAccordion.onItemClick}
          className={`order-actions-accordion-header ${
            cancelAccordion.open && "order-actions-accordion-header__open"
          }`}
        >
          <div>Cancel items</div>
          {!cancelAccordion.open ? minus : plus}
        </div>
        <div
          className={"order-actions-accordion-body"}
          style={{
            height: cancelAccordion.height,
          }}
          ref={cancelAccordion.ref}
        >
          <CancelItems orderItem={orderItem} />
        </div>
      </div>
    );
  }

  function showTabs() {
    return (
      <Tabs
        defaultActiveKey="home"
        id="uncontrolled-tab-example"
        className="mb-3 account-tabs"
      >
        <Tab
          tabClassName="account-tab"
          eventKey="home"
          title="Problem with order"
        >
          <div className="account-tab-content">
            <ProblemWithOrder />
          </div>
        </Tab>
        <Tab
          tabClassName="account-tab"
          eventKey="profile"
          title="Return or replace items"
        >
          <div className="account-tab-content">
            <ReturnOrReplaceItems orderItem={orderItem} />
          </div>
        </Tab>
        <Tab tabClassName="account-tab" eventKey="contact" title="Cancel items">
          <div className="account-tab-content">
            <CancelItems orderItem={orderItem} />
          </div>
        </Tab>
      </Tabs>
    );
  }

  return (
    <div>
      <div className="page-label">Order actions</div>
      {breakpoint && (
        <>
          {breakpoint.md && showTabs()}
          {!breakpoint.md && showAccordions()}
        </>
      )}
    </div>
  );
};
