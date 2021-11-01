import React from "react";
import ReactDOM from "react-dom";
import NavigateMenuRoutes from "@/modules/help-center/components/navigate-menu/NavigateMenuRoutes";

(() => {
  const elem = document.getElementsByClassName("help")[0];

  ReactDOM.render(<NavigateMenuRoutes />, elem);
})();
