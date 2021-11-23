import ReactDOM from "react-dom";
import React from "react";
import { FormPrice } from "@admin/modules/distributor/components/form-price/form-price";
import { SnackBar } from "@admin/modules/shared/components/snack-bar/SnackBar";

(() => {
  const elem: HTMLElement = document.querySelector(".dx-price");

  if (!elem) return;

  ReactDOM.render(
    <SnackBar>
      <FormPrice distributorId={elem?.dataset?.id} />
    </SnackBar>,
    elem
  );
})();
