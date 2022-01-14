import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import DashboardPage from "@modules/account/components/dashboard/Dashboard";

function Dashboard() {
  return (
    <PageTwoColumns>
      <DashboardPage />
    </PageTwoColumns>
  );
}

export default Dashboard;
