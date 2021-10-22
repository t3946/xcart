import React from "react";
import { Tab, Tabs } from "react-bootstrap";
import { CancelItems } from "@client/modules/account/components/orders/CancelItems";
import { ReturnOrReplaceItems } from "@client/modules/account/components/orders/ReturnOrReplaceItems";
import { ProblemWithOrder } from "@client/modules/account/components/orders/ProblemWithOrder";
import { useAccordion } from "@client/modules/account/hooks/useAccordion";
import useBreakpoint from "@client/modules/account/hooks/useBreakpoint";

interface OrderActionsPageProps {
  orderItem: any;
}

export const OrderActionsPage: React.FC<OrderActionsPageProps> = ({
  orderItem,
}) => {
  const breakpoint = useBreakpoint();
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
          <div>problemAccordion</div>
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
          <div>problemAccordion</div>
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
          <div>problemAccordion</div>
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
      {breakpoint({ xs: showAccordions, md: showTabs })}
    </div>
  );
};
