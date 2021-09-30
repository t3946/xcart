import React from "react";
import ProductReviews from "@client/jsx/modules/product/Components/ProductReviews";

(function (): void {
  const target = document.getElementById("product-reviews-target");

  if (!target) {
    return;
  }

  React.render(<ProductReviews />, target);
})();
