import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import DashboardPage from "@modules/account/components/dashboard/Dashboard";
import { getInstance } from "@services/axios/Instance";

export async function getServerSideProps(ctx: Record<any, any>) {
  const instance = getInstance(ctx.req);
  let orders: { solved: []; notSolved: [] } = null;

  await instance
    .get("/api-client/orders/get-order-groups")
    .then((res) => {
      orders = res.data;
    })
    .catch(() => {
      orders = [];
    });

  return {
    props: { orders },
  };
}

function Dashboard(props: any) {
  return (
    <PageTwoColumns>
      {props.orders && <DashboardPage {...props} />}
    </PageTwoColumns>
  );
}

export default Dashboard;
