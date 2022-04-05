import * as StripeJs from "@stripe/stripe-js";
import Store from "@redux/stores/Store";

function loadStripe() {
  const { config } = Store.getState();

  if (!config) {
    return null;
  }

  // const { stripePK } = config;
  const stripePK = "pk_test_aROFDjrZWDxMRE5YKa7keJku00ORq1PbK4";

  return StripeJs.loadStripe(stripePK, {
    locale: "en",
  });
}

export default loadStripe;
