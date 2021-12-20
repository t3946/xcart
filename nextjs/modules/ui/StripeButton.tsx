import React from "react";
import {
  PaymentRequestButtonElement,
  useStripe,
} from "@stripe/react-stripe-js";
import _merge from "lodash/merge";
import cn from "classnames";

interface IProps {
  payOptions?: {
    country: string;
    currency: string;
    total: { label: string; amount: number };
  };
  classNames?: any;
}
const StripeButton: React.FC<IProps> = (props: IProps) => {
  const clientSecret = "sk_test_4eC39HqLyjWDarjtT1zdp7dc";
  const stripe = useStripe();
  const [paymentRequest, setPaymentRequest] = React.useState<any>(null);
  const defaultOptions = {
    country: "US",
    currency: "usd",
    total: {
      label: "Demo total",
      amount: 1099,
    },
    requestPayerName: true,
    requestPayerEmail: true,
  };
  const paymentRequestOptions = _merge(defaultOptions, props.payOptions);

  React.useEffect(() => {
    if (stripe) {
      const pr = stripe.paymentRequest(paymentRequestOptions);

      pr.canMakePayment().then((result) => {
        if (result) {
          setPaymentRequest(pr);
        }
      });
    }
  }, [stripe]);

  if (paymentRequest) {
    paymentRequest.on("paymentmethod", async (e) => {
      const { paymentIntent, error: confirmError } =
        await stripe.confirmCardPayment(
          clientSecret,
          { payment_method: e.paymentMethod.id },
          { handleActions: false }
        );

      if (confirmError) {
        e.complete("fail");
      } else {
        e.complete("success");
        if (paymentIntent.status === "requires_action") {
          const { error } = await stripe.confirmCardPayment(clientSecret);

          if (error) {
            console.log({ error });
          } else {
            console.log("[paymentButton success]");
          }
        } else {
          console.log("[paymentButton success]");
        }
      }
    });

    return (
      <div className={cn(["mb-10", props.classNames])}>
        <PaymentRequestButtonElement options={{ paymentRequest }} />
      </div>
    );
  }

  return null;
};

export default StripeButton;
