import * as StripeJs from "@stripe/stripe-js";
import Store from "@redux/stores/Store";

function loadStripe() {
  const { config } = Store.getState();

  if (!config) {
    return null;
  }

  const { stripePK } = config;

  return StripeJs.loadStripe(stripePK, {
    locale: "en",
  });
}

export default loadStripe;
