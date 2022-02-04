import React from "react";
import cn from "classnames";
import AccountInfo from "@modules/account/components/dashboard/AccountInfo";
import OrderTracking from "@modules/account/components/dashboard/OrderTracking";
import Styles from "@modules/account/components/dashboard/Dashboard.module.scss";
import AccountNavigation from "./AccountNavigation";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import SliderProducts from "@modules/account/components/dashboard/SliderProducts/SliderProducts";

const Dashboard = (props) => {
  const user = useSelectorAccount((e) => e.user);

  if (!user) {
    return null;
  }

  return (
    <div className="pb-3">
      <div className={cn("d-flex", "flex-dir-column", Styles.pageColumn)}>
        <AccountInfo />
        {props.groups?.map((order) => (
          <OrderTracking orderInfo={order} />
        ))}

        <AccountNavigation />
        <SliderProducts
          classes={{
            container: ["d-none", "d-md-block", Styles.dashboard__slider],
          }}
          title="Reccomended products"
          url={"/category/featured"}
        />
      </div>
    </div>
  );
};

export default Dashboard;
