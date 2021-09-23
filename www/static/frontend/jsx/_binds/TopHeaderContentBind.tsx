import HatNavigation from "@client/jsx/modules/account/components/hat/HatNavigation";
import { accountStore } from "@client/jsx/redux/stores/StoreAccount";
import { Provider } from "react-redux";
import React from "react";
import ReactDOM from "react-dom";

(() => {
  const target = document.getElementById("top-header-content-container");

  if (!target) {
    return;
  }

  ReactDOM.render(
    <Provider store={accountStore}>
      <HatNavigation />
    </Provider>,
    target
  );
})();
