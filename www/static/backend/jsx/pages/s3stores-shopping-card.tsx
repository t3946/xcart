import React from "react";
import ReactDOM from "react-dom";
import { ShoppingCardAdmin } from "@admin/modules/admin-cruds/shopping-card/ShoppingCardAdmin";
import { BrowserRouter, Route, Switch } from "react-router-dom";

(() => {
  const elem: HTMLElement = document.querySelector(".shopping-cart-admin");

  if (!elem) return;

  ReactDOM.render(
    <BrowserRouter>
      <Switch>
        <Route
          exact
          path={"/admin/list/Cart/ShoppingCartAdmin/:cartId?"}
          component={ShoppingCardAdmin}
        />
      </Switch>
    </BrowserRouter>,
    elem
  );
})();
