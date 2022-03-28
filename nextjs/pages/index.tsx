import * as React from "react";
import { useRouter } from "next/router";
import PageTwoColumns from "../modules/account/components/layout/PageTwoColumns";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";

function Home() {
  const router = useRouter();
  const user = useSelectorAccount((e) => e.user);

  React.useEffect(() => {
    router.push(user ? "/dashboard" : "/login");
  });

  const greetings = user ? "Welcome to your account" : "Please sign in";

  return <PageTwoColumns>{greetings}</PageTwoColumns>;
}

export default Home;
