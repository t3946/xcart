import * as React from "react";
// import { AccountRouters } from "@modules/account/routers/AccountRouters";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount.ts";

function Home() {
  const user = useSelectorAccount((e) => e.user);

  console.log("user", user);

  return (
    <div>any</div>
    //   <AccountRouters />
  );
}

export default Home;
