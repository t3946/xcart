import React from "react";
import ReactDOM from "react-dom";
import { AccountRouters } from "@client/jsx/modules/account/routers/AccountRouters";
import { Provider } from "react-redux";
import Store from "@client/jsx/redux/stores/Store";

(() => {
  const elem = document.getElementsByClassName("account")[0];

  if (!elem) {
    return;
  }

  ReactDOM.render(
    (() => (
      <Provider store={Store as any}>
        <AccountRouters />
      </Provider>
    ))(),
    elem
  );
})();
