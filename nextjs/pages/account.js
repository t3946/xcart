import Account from "@modules/acccount/components/Account";
import * as React from "react";

function Home() {
  const User = {
    userId: 1,
    name: "Capitan Jack",
  };

  return <Account user={User} />;
}

export default Home;
