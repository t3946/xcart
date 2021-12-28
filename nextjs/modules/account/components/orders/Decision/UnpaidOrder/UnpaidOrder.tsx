import React from "react";
import PaymentSelection from "@modules/account/components/orders/Decision/UnpaidOrder/PaymentSelection";
import paymentItemStyles from "@modules/account/components/orders/Decision/UnpaidOrder/PaymentItem.module.scss";
import Styles from "@modules/account/components/orders/Decision/UnpaidOrder/UnpaidOrder.module.scss";
import cn from "classnames";
import DecisionsInterface from "@modules/account/ts/types/decision";
import { Formik, Form } from "formik";
import * as yup from "yup";
import PayByCardForm from "@modules/account/components/orders/Decision/UnpaidOrder/PayByCardForm";
import { CardElement } from "@stripe/react-stripe-js";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { useDispatch } from "react-redux";
import {
  payOrderByCardAction,
  cancelOrderAction,
  payOrderByPaypalAction,
} from "@redux/actions/account-actions/DecisionsActions";

interface IProps {
  onChange: (decision: DecisionsInterface) => any;
  decision: DecisionsInterface;
}

const UnpaidOrder: React.FC<IProps> = (props: IProps) => {
  const dispatch = useDispatch();
  const user = useSelectorAccount((e) => e.user);
  const { decision } = props;

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
    actions.setSubmitting(true);
    if (values.paymentMethod === "debit") {
      const { error, paymentMethod } = await stripe.createPaymentMethod({
        type: "card",
        card: elements.getElement(CardElement),
      });

      if (error) {
        actions.setErrors({ stripe: error.message });
        return;
      }

      const form = {
        id: paymentMethod.id,
        billing_details: {
          address: {
            city: "New York",
            country: "US",
            line1: "asdsadasd",
            line2: "asdasdasd",
            postal_code: "61000",
            state: "NY",
          },
          email: user.email,
          name: user.public_name,
          phone: user.phone,
        },
        decision: decision,
      };

      dispatch(
        payOrderByCardAction({
          data: form,
          success(res) {
            setTimeout(function () {
              actions.setSubmitting(false);
            }, 1000);
          },
        })
      );
    }
  }

  function submitWithoutValidationOrder(
    type: string,
    setSubmitting: (isSubmitting: boolean) => void
  ) {
    return async function () {
      setSubmitting(true);
      if (type === "cancel") {
        dispatch(
          cancelOrderAction({
            data: { decision: decision },
            success() {
              setTimeout(function () {
                setSubmitting(false);
              }, 4000);
            },
          })
        );
      } else if (type === "paypal") {
        dispatch(
          payOrderByPaypalAction({
            data: { decision: decision },
            success(res) {
              setTimeout(function () {
                setSubmitting(false);
              }, 1000);
            },
          })
        );
      }
    };
  }

  return (
    <>
      <Formik
        initialValues={initialState}
        validationSchema={validationSchema}
        onSubmit={submit}
      >
        {({
          values,
          handleChange,
          errors,
          isSubmitting,
          setSubmitting,
          touched,
          setErrors,
        }) => {
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
              <PaymentSelection
                //for formik
                checkedValue={values.paymentMethod}
                onChange={handleChange}
                disabled={isSubmitting}
                name="paymentMethod"
                options={[
                  {
                    label:
                      "Pay by Credit / Debit card, Apple Pay and Google Pay",
                    caption:
                      "Secure Visa, MasterCard, and AmEx payment through our secure server.",
                    value: "debit",
                    template: (
                      <PayByCardForm
                        isSubmitting={isSubmitting}
                        errors={errors}
                        setErrors={setErrors}
                        values={values}
                        touched={touched}
                        onChange={handleChange}
                        onStripeInit={(stripe, elements) => {
                          setStripe(stripe);
                          setElements(elements);
                        }}
                      />
                    ),
                  },
                  {
                    label: "Pay by PayPal Balance",
                    caption:
                      "Secure payment by PayPal Balance (click Create an Account to also access VISA, MC, AmEx, and Discover payments).",
                    value: "paypal",
                    template: (
                      <div>
                        <p
                          className={cn([
                            paymentItemStyles.paymentItemCaption,
                            paymentItemStyles.paymentItemCaption_accent,
                          ])}
                        >
                          You will be transferred to PayPal website to complete
                          your payment.
                        </p>

                        <div
                          className={
                            "d-flex justify-content-center justify-content-lg-start"
                          }
                        >
                          <button
                            type={"button"}
                            disabled={isSubmitting}
                            onClick={submitWithoutValidationOrder(
                              "paypal",
                              setSubmitting
                            )}
                            className={cn(["form-button", Styles.button])}
                          >
                            Pay by PayPal
                          </button>
                        </div>
                      </div>
                    ),
                  },
                ]}
              />

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
                  type="button"
                  onClick={submitWithoutValidationOrder(
                    "cancel",
                    setSubmitting
                  )}
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
