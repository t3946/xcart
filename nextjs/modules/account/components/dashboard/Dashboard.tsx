import React from "react";
import cn from "classnames";
import AccountInfo from "@modules/account/components/dashboard/AccountInfo";
import OrderTracking from "@modules/account/components/dashboard/OrderTracking";
import Styles from "@modules/account/components/dashboard/Dashboard.module.scss";
import AccountNavigation from "./AccountNavigation";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import SliderProducts from "@modules/account/components/dashboard/SliderProducts/SliderProducts";

const Dashboard = (props) => {
  console.log({props});
  const tracknum = "4HGOJJ94HGKD";
  const user = useSelectorAccount((e) => e.user);

  if (!user) {
    return null;
  }

  return (
    <div className="pb-3">
      <div className={cn("d-flex", "flex-dir-column", Styles.pageColumn)}>
        <AccountInfo />
        <OrderTracking
          orderInfo={{ number: 11 }}
          trackingInfo={{ tracknum: tracknum }}
          orderGroupInfo={{
            // dc_status: "DP"
            // dc_status: "G"
            dc_status: "S",
            // dc_status: undefined
            // dc_status: "Z"
          }}
        />
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
