import React from "react";
import ReactDOM from "react-dom";
import { FraudMainPage } from "@admin/modules/order-fraud/components/fraud-main-page";
import { SnackBar } from "@admin/modules/shared/components/snack-bar/SnackBar";

(() => {
  const elem: HTMLElement = document.querySelector(".fraud-page-order");

  if (!elem) return;

  ReactDOM.render(
    <SnackBar>
      <FraudMainPage orderId={elem?.dataset?.order} />
    </SnackBar>,
    elem
  );
})();
