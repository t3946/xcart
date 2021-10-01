import React from "react";
import ProductReviews from "@client/jsx/modules/product/Components/ProductReviews";
import { accountStore } from "@client/jsx/redux/stores/StoreAccount";
import { Provider } from "react-redux";

(function (): void {
  const target = document.getElementById("product-reviews-target");

  if (!target) {
    return;
  }

  React.render(
    <Provider store={accountStore as any}>
      <ProductReviews />
    </Provider>,
    target
  );
})();
