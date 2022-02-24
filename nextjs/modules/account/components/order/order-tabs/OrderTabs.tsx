import React from "react";
import { Tab, Tabs } from "react-bootstrap";
import { ProblemWithOrder } from "@modules/account/components/orders/ProblemWithOrder";
import { ReturnOrReplaceItems } from "@modules/account/components/orders/ReturnOrReplaceItems";
import { CancelItems } from "@modules/account/components/orders/CancelItems";
import cn from "classnames";
import {
  getOrderReturnProducts,
  getOrderCancelProducts,
} from "@modules/account/components/order/ts/getOrderReturnProducts";
import Styles from "@modules/account/components/order/order-tabs/OrderTabs.module.scss";

export const OrderTabs: React.FC = (props) => {
  const { orderItem } = props;
  const [tab, setTab] = React.useState("home");

  const getTabClasses = (key: string) => {
    return cn({ [Styles.tab_active]: key === tab });
  };

  const orderReturnProducts = getOrderReturnProducts(orderItem.groups);
  const cancelProducts = getOrderCancelProducts(orderItem.groups);

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

      {orderReturnProducts.length > 0 && (
        <Tab
          tabClassName={getTabClasses("profile")}
          eventKey="profile"
          title="Return or replace items"
        >
          <div className={Styles.tabContent}>
            <ReturnOrReplaceItems
              orderItem={orderItem}
              products={orderReturnProducts}
            />
          </div>
        </Tab>
      )}

      {cancelProducts.length > 0 && (
        <Tab
          tabClassName={getTabClasses("contact")}
          eventKey="contact"
          title="Cancel items"
        >
          <div className={Styles.tabContent}>
            <CancelItems orderItem={orderItem} products={cancelProducts} />
          </div>
        </Tab>
      )}
    </Tabs>
  );
};
