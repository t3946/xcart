import * as React from "react";
import { useRouter } from "next/router";
import PageTwoColumns from "../modules/account/components/layout/PageTwoColumns";

function Home() {
  const router = useRouter();

  React.useEffect(() => {
    router.push("/dashboard");
  });

  return <PageTwoColumns>Welcome to your account!</PageTwoColumns>;
}

export default Home;
