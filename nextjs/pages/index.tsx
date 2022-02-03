import * as React from "react";
import { useRouter } from "next/router";
import PageTwoColumns from "../modules/account/components/layout/PageTwoColumns";
import DashboardPage from "@modules/account/components/dashboard/Dashboard";

function Home() {
  const router = useRouter();

  React.useEffect(() => {
    router.replace("/dashboard", undefined, { shallow: true });
  });

  return (
    <PageTwoColumns>
      <DashboardPage />
    </PageTwoColumns>
  );
}

export default Home;
