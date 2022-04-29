import { render } from "react";
import FooterPaymentMethods from "@client/jsx/components/footer-payment-methods/FooterPaymentMethods";

$(() => {
  const target = $(".footer-payment-methods-container")[0];

  if (!target) {
    return;
  }

  const payments = JSON.parse(target.getAttribute("data-payment-methods"));

  render(<FooterPaymentMethods payments={payments} />, target);
});
