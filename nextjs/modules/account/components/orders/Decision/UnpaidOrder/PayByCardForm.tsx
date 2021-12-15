import React from "react";
import { loadStripe } from "@stripe/stripe-js";
import { CardElement, Elements } from "@stripe/react-stripe-js";
import * as yup from "yup";
import StripeButton from "@modules/ui/StripeButton";
import StripeField from "@modules/ui/StripeField";
import SliderSwitchButton from "@modules/ui/SliderSwitchButton";
import AppData from "@utils/AppData";
import { Formik, Form } from "formik";
import { Form as RBForm } from "react-bootstrap";
import cn from "classnames";
import unpaidOrderStyles from "@modules/account/components/orders/Decision/UnpaidOrder/UnpaidOrder.module.scss";
import Styles from "@modules/account/components/orders/Decision/UnpaidOrder/PayByCardForm.module.scss";
import { payOrderAction } from "@redux/actions/account-actions/DecisionsActions";
import { useDispatch } from "react-redux";
import DecisionsInterface from "@modules/account/ts/types/decision";

interface IProps {
  onChangeDecision: (decision: DecisionsInterface) => any;
  decision: DecisionsInterface;
}

const PayByCardForm: React.FC<IProps> = (props: IProps) => {
  const stripePromise = loadStripe(AppData.stripeSettings?.publicKey, {
    locale: "en",
  });
  const { onChangeDecision, decision } = props;
  const initialValues = {
    cardholderName: "",
    stripe: "",
    billingSameAsShipping: false,
  };
  const [stripe, setStripe] = React.useState<any>(null);
  const [elements, setElements] = React.useState<any>(null);
  const [stripeReady, setStripeReady] = React.useState(false);
  const [stripeError, setStripeError] = React.useState("");
  const dispatch = useDispatch();
  function stripeInitHandler({ stripe, elements }) {
    setStripe(stripe);
    setElements(elements);
  }

  const validationSchema = yup.object().shape({
    cardholderName: yup
      .string()
      .max(40, "Max length is 40 character")
      .min(2, "Min length is 2 character")
      .required("Cardholder name is a required field"),
  });

  const submit = async (values, { setSubmitting }) => {
    if (!stripe || !elements) {
      console.log("stripe.js is not loaded");
      return;
    }
    if (stripeError === "") {
      const { error, paymentMethod } = await stripe.createPaymentMethod({
        type: "card",
        card: elements.getElement(CardElement),
      });
      if (error) {
        setStripeError(error.message);
        return;
      }
    } else {
      return;
    }

    const formData = new FormData();
    formData.append("cardholderName", values.cardholderName);
    formData.append("billingSameAsShipping", values.billingSameAsShipping);

    dispatch(
      payOrderAction({
        data: formData,
        success(res: DecisionsInterface) {
          onChangeDecision(res);
          setSubmitting(false);
        },
      })
    );
  };

  return (
    <Formik
      initialValues={initialValues}
      onSubmit={submit}
      validationSchema={validationSchema}
      ref={React.useRef()}
    >
      {({ errors, values, isSubmitting, handleChange, touched }) => {
        const stripeCardElemProps = {
          afterInit: stripeInitHandler,
          onReady: () => setStripeReady(true),
          error: stripeError,
          setError: setStripeError,
        };
        return (
          <Form className={cn([Styles.form])}>
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
              <RBForm.Control
                name="cardholderName"
                disabled={isSubmitting}
                className={cn([
                  "form-input",
                  Styles.formInput,
                  {
                    "is-invalid":
                      errors.cardholderName && touched.cardholderName,
                  },
                ])}
                isInvalid={!!(errors.cardholderName && touched.cardholderName)}
                isValid={!!(!errors.cardholderName && touched.cardholderName)}
                value={values.cardholderName}
                onChange={handleChange}
              />
              {errors.cardholderName && touched.cardholderName && (
                <RBForm.Control.Feedback className="d-block" type="invalid">
                  {errors.cardholderName}
                </RBForm.Control.Feedback>
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
                <StripeField
                  {...stripeCardElemProps}
                  className={cn([Styles.formInput])}
                />
              </Elements>
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
              Is Billing Address the same as Shipping Adress?
            </div>
            <SliderSwitchButton
              disabled={isSubmitting}
              checked={values.billingSameAsShipping}
              name="billingSameAsShipping"
              onChange={handleChange}
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
                type="submit"
                disabled={!stripeReady || isSubmitting}
                className={cn([
                  "form-button",
                  "w-lg-auto",
                  "fw-bold",
                  unpaidOrderStyles.button,
                  unpaidOrderStyles.decision__button,
                ])}
              >
                {" "}
                Submit payment
              </button>
            </div>
          </Form>
        );
      }}
    </Formik>
  );
};

export default PayByCardForm;
