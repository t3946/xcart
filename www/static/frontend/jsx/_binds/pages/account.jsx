import React from "react";
import ReactDOM from "react-dom";
import { AccountRouters } from "../../modules/account/routers/AccountRouters";

(() => {
  const elem = document.getElementsByClassName("account")[0];

  ReactDOM.render(<AccountRouters />, elem);
})();
