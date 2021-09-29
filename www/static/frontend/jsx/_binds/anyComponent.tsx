import { Provider } from "react-redux";
import { accountStore } from "@client/jsx/redux/stores/StoreAccount";
import { AccountRouters } from "@client/modules/account/routers/AccountRouters";
import React from "react";

const AnyComponent = () => {
  return (
    <Provider store={accountStore}>
      <AccountRouters />
    </Provider>
  );
};

export default AnyComponent;