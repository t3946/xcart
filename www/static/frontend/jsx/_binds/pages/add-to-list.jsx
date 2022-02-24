import React, { useEffect } from "react";
import ReactDOM from "react-dom";
import { Provider } from "react-redux";
import Store from "@client/jsx/redux/stores/Store";
import { AddToListSelectOnProductPage } from "../../modules/account/components/lists/AddToListSelectOnProductPage";
import Snackbar from "../../modules/account/components/snackbar/Snackbar";

(() => {
  const elem = document.getElementsByClassName(
    "product-page-add-to-list-btn"
  )[0];

  if (!elem) {
    return;
  }

  const outOfStock = elem.getAttribute("data-out-of-stock") === "1";

  if (outOfStock) {
    return;
  }

  ReactDOM.render(
    (() => (
      <Provider store={Store}>
        <Snackbar>
          <AddToListSelectOnProductPage id={"add-to-list-btn"} />
        </Snackbar>
      </Provider>
    ))(),
    elem
  );
})();
