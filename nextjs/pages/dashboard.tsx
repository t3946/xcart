import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import DashboardPage from "@modules/account/components/dashboard/Dashboard";
import { getInstance } from "@services/axios/Instance";

export async function getServerSideProps(ctx: Record<any, any>) {
  const instance = getInstance(ctx.req);
  let groups: { solved: []; notSolved: [] };

  await instance.get("/api-client/orders/get-order-groups").then((res) => {
    groups = res.data;
  });
  return {
    props: { groups },
  };
}

function Dashboard(props) {
  return (
    <PageTwoColumns>
      <DashboardPage {...props} />
    </PageTwoColumns>
  );
}

export default Dashboard;
