import React from "react";
import ReactDOM from "react-dom";
import { ShoppingCardAdmin } from "@admin/modules/admin-cruds/shopping-card/ShoppingCardAdmin";

(() => {
  const elem: HTMLElement = document.querySelector(".shopping-cart-admin");

  if (!elem) return;

  ReactDOM.render(<ShoppingCardAdmin />, elem);
})();
