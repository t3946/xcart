import React from "react";
import ReactDOM from "react-dom";
import { AccountRouters } from "../../modules/account/routers/AccountRouters";
import { Provider } from "react-redux";
import { accountStore } from "../../redux/stores/StoreAccount";

(() => {
  const elem = document.getElementsByClassName("account")[0];

  if (!elem) {
    return;
  }

  ReactDOM.render(
    (() => (
      <Provider store={accountStore}>
        <AccountRouters />
      </Provider>
    ))(),
    elem
  );
})();
