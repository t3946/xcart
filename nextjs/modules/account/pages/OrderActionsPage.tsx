import React, { useEffect } from "react";
import { ProblemWithOrder } from "@modules/account/components/orders/ProblemWithOrder";
import { useAccordion } from "@modules/account/hooks/useAccordion";
import { getBreakpointsFlags } from "@modules/account/hooks/useBreakpoint";
import Store from "@redux/stores/Store";
import { setBreakpoint } from "@redux/actions/account-actions/MainActions";
import { useSelector } from "react-redux";
import { AccountStore } from "@modules/account/ts/types/store.type";
import { OrderView } from "@modules/account/ts/types/order/order-view.types";
import { OrderTabs } from "@modules/account/components/order/order-tabs/OrderTabs";
import { ReturnOrReplaceItems } from "@modules/account/components/orders/ReturnOrReplaceItems";
import { CancelItems } from "@modules/account/components/orders/CancelItems";

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

interface OrderActionsPage {
  orderItem: OrderView;
}

export const OrderActionsPage: React.FC<OrderActionsPage> = (props) => {
  const { orderItem } = props;
  useEffect(() => {
    Store.dispatch(setBreakpoint(getBreakpointsFlags(window.innerWidth)));
  }, []);
  const breakpoint = useSelector((e: AccountStore) => e.main.breakpoint);

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

  return (
    <div>
      <div className="page-label">Order actions</div>
      {breakpoint && (
        <>
          {breakpoint.md && <OrderTabs orderItem={orderItem} />}
          {!breakpoint.md && showAccordions()}
        </>
      )}
    </div>
  );
};
