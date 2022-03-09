import React from "react";
import ProductReviews from "@client/jsx/modules/product/Components/ProductReviews";
import Store from "@client/jsx/redux/stores/Store";
import { Provider } from "react-redux";

(function (): void {
  const target = document.getElementById("product-reviews-target");

  if (!target) {
    return;
  }

  React.render(
    <Provider store={Store as any}>
      <ProductReviews />
    </Provider>,
    target
  );
})();
