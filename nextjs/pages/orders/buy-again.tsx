import * as React from "react";
import PageTwoColumns from "@modules/account/components/layout/PageTwoColumns";
import BuyAgain from "@components/pages/orders/BuyAgain";
import { useRouter } from "next/router";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";

function Dashboard() {
  const router = useRouter();
  const user = useSelectorAccount((e) => e.user);

  React.useEffect(() => {
    if (!user) {
      router.push("/login");
    }
  });

  return (
    <PageTwoColumns>
      <BuyAgain />
    </PageTwoColumns>
  );
}

export default Dashboard;
