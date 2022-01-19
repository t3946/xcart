import React from "react";
import { Tab, Tabs } from "react-bootstrap";
import { ProblemWithOrder } from "@modules/account/components/orders/ProblemWithOrder";
import { ReturnOrReplaceItems } from "@modules/account/components/orders/ReturnOrReplaceItems";
import { CancelItems } from "@modules/account/components/orders/CancelItems";

export const OrderTabs: React.FC = (props) => {
  const { orderItem } = props;

  return (
    <Tabs
      defaultActiveKey="home"
      id="uncontrolled-tab-example"
      className={`mb-3 account-tabs`}
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
};
