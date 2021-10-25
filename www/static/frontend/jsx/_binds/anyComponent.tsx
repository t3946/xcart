import { Provider } from "react-redux";
import Store from "@client/jsx/redux/stores/Store";
import { AccountRouters } from "@client/modules/account/routers/AccountRouters";
import React from "react";

const AnyComponent: React.FC = () => {
  return (
    <Provider store={Store as any}>
      <AccountRouters />
    </Provider>
  );
};

export default AnyComponent;
