import React from "react";
import ModalCalculateShipping from "@/modules/cart/components/modal-calculate-shipping";

(() => {
  if ($("#calculate-shipping-target")[0]) {
    React.render(<ModalCalculateShipping />, $("#calculate-shipping-target")[0]);
  }
})();
