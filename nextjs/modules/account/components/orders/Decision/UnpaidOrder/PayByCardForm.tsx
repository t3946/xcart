import React from "react";
import { loadStripe } from "@stripe/stripe-js";
import { Elements } from "@stripe/react-stripe-js";
import StripeButton from "@modules/ui/StripeButton";
import StripeField from "@modules/ui/StripeField";
import SliderSwitchButton from "@modules/ui/SliderSwitchButton";
import { Form as RBForm } from "react-bootstrap";
import cn from "classnames";
import unpaidOrderStyles from "@modules/account/components/orders/Decision/UnpaidOrder/UnpaidOrder.module.scss";
import Styles from "@modules/account/components/orders/Decision/UnpaidOrder/PayByCardForm.module.scss";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import Input from "@modules/ui/forms/Input";
import Feedback from "@modules/ui/forms/Feedback";

interface IProps {
  isSubmitting: boolean;
  errors: Record<any, any>;
  values: Record<any, any>;
  touched: Record<any, any>;
  disabled?: boolean;
  onChange: (e: any) => void;
  setErrors: (e: Record<any, any>) => void;
  onStripeInit: (stripe: any, elements: any) => void;
}

const PayByCardForm: React.FC<IProps> = (props: IProps) => {
  const {
    isSubmitting,
    errors,
    values,
    touched,
    onChange,
    setErrors,
    onStripeInit,
  } = props;

  const stripePublicKey = "pk_test_TYooMQauvdEDq54NiTphI7jx";
  const { APP_LOCAL } = useSelectorAccount((e) => e.config);

  if (!APP_LOCAL) {
    stripePublicKey = useSelectorAccount((e) => e.config.stripePublicKey);
  }

  const [stripePromise] = React.useState(
    loadStripe(stripePublicKey, {
      locale: "en",
    })
  );

  const [stripe, setStripe] = React.useState<any>(null);
  const [elements, setElements] = React.useState<any>(null);
  const [stripeReady, setStripeReady] = React.useState(false);
  const stripeCardElemProps = {
    afterInit: stripeInitHandler,
    onReady: () => setStripeReady(true),
    options: { disabled: isSubmitting },
    error: errors.stripe,
    setError: (e: string) => {
      setErrors({ stripe: e });
    },
  };

  function stripeInitHandler({ stripe, elements }) {
    setStripe(stripe);
    setElements(elements);
    onStripeInit(stripe, elements);
  }

  return (
    <div className={Styles.form}>
      <Elements stripe={stripePromise}>
        <StripeButton classNames={Styles.formInput} />
      </Elements>

      <RBForm.Group className="mb-3 mb-md-4 mb-lg-12">
        <RBForm.Label
          className={cn([
            Styles.form__inputLabel,
            Styles.formInputLabel,
            "form-input-label",
            "form-input-label__required",
          ])}
        >
          Cardholder name
        </RBForm.Label>

        <Input
          name="cardholderName"
          value={values.cardholderName}
          onChange={onChange}
          disabled={isSubmitting}
          isInvalid={!!(errors.cardholderName && touched.cardholderName)}
          isValid={!!(!errors.cardholderName && touched.cardholderName)}
        />

        {errors.cardholderName && touched.cardholderName && (
          <Feedback className="d-block" type="invalid">
            {errors.cardholderName}
          </Feedback>
        )}
      </RBForm.Group>

      <RBForm.Group className="mb-2 mb-md-12">
        <RBForm.Label
          className={cn([
            Styles.form__inputLabel,
            Styles.formInputLabel,
            "form-input-label form-input-label__required",
          ])}
        >
          Credit / Debit card details
        </RBForm.Label>

        <Elements stripe={stripePromise}>
          <StripeField className={Styles.formInput} {...stripeCardElemProps} />
        </Elements>

        <Feedback
          type={errors.stripe ? "invalid" : "valid"}
          className={cn({
            "d-none": errors.stripe === "",
            "d-block": errors.stripe !== "",
          })}
        >
          {errors.stripe}
        </Feedback>
      </RBForm.Group>

      <div className={cn([Styles.formCaption, Styles.form__caption])}>
        Your card will be charged in the amount of USA of{" "}
        <b className="text-dark">US$ 427.06</b> by S3 Stores, Inc.
      </div>

      <div
        className={cn([
          "form-input-label",
          "mb-3",
          Styles.form__inputLabel,
          Styles.formInputLabel,
        ])}
      >
        Is Billing Address the same as Shipping Address?
      </div>

      <SliderSwitchButton
        disabled={isSubmitting}
        checked={values.billingSameShipping}
        name="billingSameShipping"
        onChange={onChange}
      />

      <div
        className={cn([
          "mt-4",
          "d-flex",
          "d-lg-block",
          "justify-content-center",
        ])}
      >
        <button
          disabled={!stripeReady || isSubmitting}
          className={cn([
            "form-button",
            "w-lg-auto",
            "fw-bold",
            unpaidOrderStyles.button,
          ])}
        >
          Submit payment
        </button>
      </div>
    </div>
  );
};

export default PayByCardForm;
