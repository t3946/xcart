import React from "react";
import cn from "classnames";
import AccountInfo from "@modules/account/components/dashboard/AccountInfo";
import OrderTracking from "@modules/account/components/dashboard/OrderTracking";
import AccountNavigation from "./AccountNavigation";
import { useRouter } from "next/router";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";

import Styles from "@modules/account/components/dashboard/Dashboard.module.scss";

const Dashboard = () => {
  const router = useRouter();
  const user = useSelectorAccount((e) => e.user);
  if (!user) {
    router.push("/login");
    return <>no user</>;
  }

  const tracknum = "4HGOJJ94HGKD";

  return (
    <div className="py-3">
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
      </div>
    </div>
  );
};

export default Dashboard;
