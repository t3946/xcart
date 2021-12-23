import React from "react";
import ReactDOM from "react-dom";
import NavigateMenuRoutes from "@/modules/help-center/components/navigate-menu/NavigateMenuRoutes";

(() => {
  const elem = document.querySelector(".help");

  if (!elem) return;

  ReactDOM.render(<NavigateMenuRoutes />, elem);
})();
