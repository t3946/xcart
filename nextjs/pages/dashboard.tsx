import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import DashboardPage from "@modules/account/components/dashboard/Dashboard";
import { getInstance } from "@services/axios/Instance";
import { useRouter } from "next/router";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";

export async function getServerSideProps(ctx: Record<any, any>) {
  const instance = getInstance(ctx.req);
  let groups: { solved: []; notSolved: [] } = null;

  await instance
    .get("/api-client/orders/get-order-groups")
    .then((res) => {
      groups = res.data;
    })
    .catch((err) => {
      console.log(err);
    });

  return {
    props: { groups },
  };
}

function Dashboard(props: any) {
  const router = useRouter();
  const user = useSelectorAccount((e) => e.user);

  React.useEffect(() => {
    if (!user) {
      router.push("/login");
    }
  });

  return (
    <PageTwoColumns>
      {props.groups && <DashboardPage {...props} />}
    </PageTwoColumns>
  );
}

export default Dashboard;
