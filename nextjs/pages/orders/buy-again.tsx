import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import BuyAgain from "@components/pages/orders/BuyAgain";

function Dashboard() {
  return (
    <PageTwoColumns>
      <BuyAgain />
    </PageTwoColumns>
  );
}

export default Dashboard;
