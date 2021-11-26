import * as React from "react";
import { loadStripe } from "@stripe/stripe-js";
import {
  CardElement,
  Elements,
  useStripe,
  useElements,
  CardElementProps,
} from "@stripe/react-stripe-js";
import AppData from "@client/jsx/utils/AppData";
import _merge from "lodash/merge";
import * as stripe from "stripe";
import { Formik, Form } from "formik";
import { Form as RBForm } from "react-bootstrap";
import cn from "classnames";

interface IStripeProps {
  afterInit: (e: any) => any;
  cardElementProps?: CardElementProps;
}

const StripeField: React.FC<IStripeProps> = function (props: IStripeProps) {
  const stripe = useStripe();
  const elements = useElements();
  const cardRef = React.useRef<any>();

  React.useEffect(function () {
    props.afterInit({
      stripe,
      elements,
    });
  });

  const defaultProps = {
    options: {
      classes: {
        base: "w-100",
      },
    },
    ref: cardRef,
  };

  const cardElementProps = _merge(defaultProps, props.cardElementProps);

  return (
    <div
      className="form-input d-flex align-items-center"
      onClick={() => {
        if (!cardRef.current) {
          return;
        }

        cardRef.current.base.click();
      }}
    >
      <CardElement {...cardElementProps} />
    </div>
  );
};

const Checkout: React.FC = function () {
  const initialValues = {
    stripe: "",
  };
  const [stripe, setStripe] = React.useState<any>(null);
  const [elements, setElements] = React.useState<any>(null);
  const [stripeReady, setStripeReady] = React.useState(false);

  const handleSubmit = async (event) => {
    console.log("handleSubmit", {
      stripe,
    });

    event.preventDefault();

    if (!elements || !stripe) {
      return;
    }

    const { error, paymentMethod } = await stripe.createPaymentMethod({
      type: "card",
      card: elements.getElement(CardElement),
    });

    console.log("submit", { error, paymentMethod });
  };

  function stripeInitHandler({ stripe, elements }) {
    console.log("stripeInitHandler", { stripe, elements });
    setStripe(stripe);
    setElements(elements);
  }

  function submit() {
    console.log("submit");
  }

  const [stripeError, setStripeError] = React.useState("");

  return (
    <Formik
      initialValues={initialValues}
      // validationSchema={validationSchema}
      onSubmit={submit}
      ref={React.useRef()}
    >
      {({ errors, values }) => {
        const stripeCardElemProps: CardElementProps = {
          onReady: () => setStripeReady(true),
          onChange: function (e) {
            const errorMessage = e.error ? e.error.message : "";

            setStripeError(errorMessage);
          },
        };

        console.log("Formik", { errors, values });

        return (
          <Form className={"mt-5"}>
            <RBForm.Group controlId="RegisterFormName">
              <RBForm.Label className={"form-input-label"}>
                Your Credit Card
              </RBForm.Label>

              <StripeField
                afterInit={stripeInitHandler}
                cardElementProps={stripeCardElemProps}
              />

              <RBForm.Control.Feedback
                type="invalid"
                className={cn({
                  "d-none": stripeError === "",
                  "d-block": stripeError !== "",
                })}
              >
                {stripeError}
              </RBForm.Control.Feedback>
            </RBForm.Group>

            <button
              type="submit"
              disabled={!stripeReady}
              className={"form-button mt-3"}
            >
              Pay
            </button>
          </Form>
        );
      }}
    </Formik>
  );
};

const PaymentRequired: React.FC = function () {
  const stripePromise = loadStripe(AppData.stripeSettings.publicKey, {
    locale: "en",
  });

  return (
    <Elements stripe={stripePromise}>
      <Checkout />
    </Elements>
  );
};

export default PaymentRequired;
