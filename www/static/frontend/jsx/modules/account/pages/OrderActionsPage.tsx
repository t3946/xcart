import React from "react";
import { Tab, Tabs } from "react-bootstrap";
import { CancelItems } from "@client/modules/account/components/orders/CancelItems";
import { ReturnOrReplaceItems } from "@client/modules/account/components/orders/ReturnOrReplaceItems";
import { ProblemWithOrder } from "@client/modules/account/components/orders/ProblemWithOrder";

interface OrderActionsPageProps {
  orderItem: any;
}

export const OrderActionsPage: React.FC<OrderActionsPageProps> = ({
  orderItem,
}) => {
  return (
    <div>
      <div className="page-label">Order actions</div>
      <Tabs
        defaultActiveKey="profile"
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
    </div>
  );
};
