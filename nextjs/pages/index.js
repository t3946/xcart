import * as React from "react";
import { AccountRouters } from "@modules/account/routers/AccountRouters";
import { Provider } from "react-redux";
import Store from "@redux/stores/Store";

function Home() {
  return (
    <div>
      <Provider store={Store}>
        <AccountRouters />
      </Provider>
    </div>
  );
}

export default Home;
