import React from "react";
import PaymentSelection from "@modules/account/components/orders/Decision/UnpaidOrder/PaymentSelection";
import Styles from "@modules/account/components/orders/Decision/UnpaidOrder/UnpaidOrder.module.scss";
import cn from "classnames";
import DecisionsInterface from "@modules/account/ts/types/decision";
import { Formik, Form } from "formik";
import * as yup from "yup";
import PayByCardForm from "@modules/account/components/orders/Decision/UnpaidOrder/PayByCardForm";
import { CardElement } from "@stripe/react-stripe-js";

interface IProps {
  onChange: (decision: DecisionsInterface) => any;
  decision: DecisionsInterface;
}

const UnpaidOrder: React.FC<IProps> = (props: IProps) => {
  const { onChangeDecision, decision } = props;
  const classes = {
    p: [
      "estimate-table-caption",
      "estimate-table__caption",
      Styles.decisionCaption,
    ],
  };
  const initialState = {
    paymentMethod: "debit",
    cardholderName: "",
    stripe: "",
    billingSameShipping: false,
  };
  const validationSchema = yup.object().shape({
    paymentMethod: yup.string(),
    cardholderName: yup
      .string()
      .max(40, "Max length is 40 character")
      .min(2, "Min length is 2 character")
      .required("Cardholder name is a required field"),
  });
  const [createPaymentMethod, setCreatePaymentMethod] = React.useState(null);
  const [stripe, setStripe] = React.useState(null);
  const [elements, setElements] = React.useState(null);

  async function submit(values: Record<any, any>, actions: Record<any, any>) {
    console.log("SUBMIT START");
    actions.setSubmitting(true);

    const { error, paymentMethod } = await stripe.createPaymentMethod({
      type: "card",
      card: elements.getElement(CardElement),
    });

    if (error) {
      console.log("SUBMIT STRIPE ERRORS", error);
      return;
    }

    console.log("SUBMIT STRIPE PAYMENT METHOD", paymentMethod);

    setTimeout(function () {
      actions.setSubmitting(false);
      console.log("SUBMIT END");
    }, 4000);
  }

  return (
    <>
      <Formik
        initialValues={initialState}
        validationSchema={validationSchema}
        onSubmit={submit}
      >
        {({ values, handleChange, errors, isSubmitting, touched }) => {
          return (
            <Form>
              <h1
                className={cn([
                  "decision-inner-header",
                  Styles.decision__title,
                ])}
              >
                Unpaid order
              </h1>

              <p
                className={cn([classes.p, Styles.decision__caption_lineIndent])}
              >
                We received your order but it hasn't been paid for yet.
              </p>

              <p
                className={cn([
                  classes.p,
                  Styles.decision__caption_paragraphIndent,
                ])}
              >
                Please pay for the order so that we can process it.
              </p>

              <PayByCardForm
                isSubmitting={isSubmitting}
                errors={errors}
                values={values}
                touched={touched}
                onChange={handleChange}
                onStripeInit={(stripe, elements) => {
                  setStripe(stripe);
                  setElements(elements);
                }}
              />

              {/*<PaymentSelection*/}
              {/*  checkedValue={values.paymentMethod}*/}
              {/*  fieldName={"paymentMethod"}*/}
              {/*  onChange={handleChange}*/}
              {/*  decision={decision}*/}
              {/*  errors={errors}*/}
              {/*  values={values}*/}
              {/*  touched={touched}*/}
              {/*  isSubmitting={isSubmitting}*/}
              {/*  setCreatePaymentMethod={(e) => {*/}
              {/*    console.log("")*/}
              {/*    if (!createPaymentMethod) {*/}
              {/*      console.log('set e')*/}
              {/*      setCreatePaymentMethod(e);*/}
              {/*    }*/}
              {/*  }}*/}
              {/*/>*/}

              <p className={cn([classes.p, "m-0", Styles.widthMaxContent])}>
                Alternatively you can cancel the order.
              </p>

              <div
                className={cn([
                  "d-flex",
                  "justify-content-center",
                  "justify-content-lg-start",
                ])}
              >
                <button
                  className={cn([
                    "form-button",
                    "form-button__outline",
                    "fw-bold",
                    "mt-4",
                    Styles.button,
                    Styles.decision__button,
                    Styles.decision__button_cancelOrder,
                  ])}
                  disabled={isSubmitting}
                >
                  Cancel order
                </button>
              </div>
            </Form>
          );
        }}
      </Formik>
    </>
  );
};

export default UnpaidOrder;
