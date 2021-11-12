import React from "react";
import ReactDOM from "react-dom";
import { Provider } from "react-redux";
import { FraudMainPage } from "@admin/modules/order-fraud/components/fraud-main-page";
import { SnackBar } from "@admin/modules/shared/components/snack-bar/SnackBar";
import { fraudCheckStore } from "@redux/stores/fraudCheckStore";

(() => {
  const elem: HTMLElement = document.querySelector(".fraud-page-order");

  if (!elem) return;

  ReactDOM.render(
    <Provider store={fraudCheckStore as any}>
      <SnackBar>
        <FraudMainPage orderId={elem?.dataset?.order} />
      </SnackBar>
    </Provider>,
    elem
  );
})();
