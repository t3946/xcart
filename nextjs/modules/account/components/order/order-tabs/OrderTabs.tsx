import React from "react";
import { Tab, Tabs } from "react-bootstrap";
import { ProblemWithOrder } from "@modules/account/components/orders/ProblemWithOrder";
import { ReturnOrReplaceItems } from "@modules/account/components/orders/ReturnOrReplaceItems";
import { CancelItems } from "@modules/account/components/orders/CancelItems";
import cn from "classnames";

import Styles from "@modules/account/components/order/order-tabs/OrderTabs.module.scss";

export const OrderTabs: React.FC = (props) => {
  const { orderItem } = props;
  const [tab, setTab] = React.useState("home");

  const getTabClasses = (key: string) => {
    return cn({ [Styles.tab_active]: key === tab });
  };

  return (
    <Tabs
      activeKey={tab}
      onSelect={(k) => setTab(k)}
      id="uncontrolled-tab-example"
      className={cn(`mb-3 account-tabs`, Styles.tabs)}
    >
      <Tab
        tabClassName={getTabClasses("home")}
        eventKey="home"
        title="Problem with order"
      >
        <div className={Styles.tabContent}>
          <ProblemWithOrder />
        </div>
      </Tab>

      <Tab
        tabClassName={getTabClasses("profile")}
        eventKey="profile"
        title="Return or replace items"
      >
        <div className={Styles.tabContent}>
          <ReturnOrReplaceItems orderItem={orderItem} />
        </div>
      </Tab>

      <Tab
        tabClassName={getTabClasses("contact")}
        eventKey="contact"
        title="Cancel items"
      >
        <div className={Styles.tabContent}>
          <CancelItems orderItem={orderItem} />
        </div>
      </Tab>
    </Tabs>
  );
};
