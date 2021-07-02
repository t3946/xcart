import React from "react";
import ReactDOM from "react-dom";
import { AccountRouters } from "../../../../temp/frontend/js/main";

(() => {
  const elem = document.getElementsByClassName("account")[0];

  ReactDOM.render(<AccountRouters />, elem);
})();
