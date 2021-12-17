import * as React from "react";
import { loadStripe } from "@stripe/stripe-js";
import {
  CardElement,
  Elements,
  useStripe,
  useElements,
  CardElementProps,
} from "@stripe/react-stripe-js";
import AppData from "@utils/AppData";
import _merge from "lodash/merge";
import { Formik, Form } from "formik";
import { Form as RBForm } from "react-bootstrap";
import cn from "classnames";
import SliderSwitchButton from "@modules/ui/SliderSwitchButton";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import DecisionsInterface from "@modules/account/ts/types/decision";

interface IStripeProps extends CardElementProps {
  afterInit: (e: any) => any;
}

const StripeField: React.FC<IStripeProps> = function (props: IStripeProps) {
  const stripe = useStripe();
  const elements = useElements();
  const cardRef = React.useRef<any>();
  const defaultProps = {
    options: {
      classes: {
        base: "w-100",
      },
    },
    // ref: cardRef,
  };
  const cardElementProps = _merge(defaultProps);
  const [hasErrors, setHasErrors] = React.useState(false);
  const classes = {
    stripeWrapper: [
      "form-input",
      "d-flex",
      "align-items-center",
      {
        "form-input_error": hasErrors,
      },
    ],
  };

  React.useEffect(function () {
    props.afterInit({
      stripe,
      elements,
    });
  });

  return (
    <div
      className={cn(classes.stripeWrapper)}
      onClick={() => {
        if (!cardRef.current) {
          return;
        }

        cardRef.current.base.click();
      }}
    >
      <CardElement
        {...cardElementProps}
        onChange={(e) => {
          props.onChange && props.onChange(e);
          setHasErrors(!!e.error);
        }}
        onReady={(e) => {
          props.onReady && props.onReady(e);
        }}
      />
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
  const [stripeError, setStripeError] = React.useState("");

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
    setStripe(stripe);
    setElements(elements);
  }

  function submit() {
    console.log("submit");
  }

  return (
    <Formik
      initialValues={initialValues}
      // validationSchema={validationSchema}
      onSubmit={submit}
    >
      {({ errors, values }) => {
        const stripeCardElemProps = {
          afterInit: stripeInitHandler,
          onReady: () => setStripeReady(true),
          onChange: function (e) {
            const errorMessage = e.error ? e.error.message : "";

            setStripeError(errorMessage);
          },
        };

        return (
          <Form className={"mt-5"}>
            <RBForm.Group controlId="RegisterFormName">
              <RBForm.Label className={"form-input-label"}>
                Your Credit Card
              </RBForm.Label>

              <StripeField {...stripeCardElemProps} />

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

            <SliderSwitchButton />
          </Form>
        );
      }}
    </Formik>
  );
};

interface IProps {
  onChange: (decision: DecisionsInterface) => any;
  decision: DecisionsInterface;
}

const PaymentRequired: React.FC<IProps> = function (props: IProps) {
  const publicKey = useSelectorAccount((e) => e.config.stripePublicKey);
  const stripePromise = loadStripe(publicKey, {
    locale: "en",
  });

  return (
    <Elements stripe={stripePromise}>
      <Checkout />
    </Elements>
  );
};

export default PaymentRequired;
