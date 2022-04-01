import React from "react";
import PaymentSelection from "@modules/account/components/orders/Decision/UnpaidOrder/PaymentSelection";
import paymentItemStyles from "@modules/account/components/orders/Decision/UnpaidOrder/PaymentItem.module.scss";
import Styles from "@modules/account/components/orders/Decision/UnpaidOrder/UnpaidOrder.module.scss";
import cn from "classnames";
import DecisionsInterface from "@modules/account/ts/types/decision";
import { Form, Formik } from "formik";
import * as yup from "yup";
import { useDispatch } from "react-redux";
import { solveDecisionAction } from "@redux/actions/account-actions/DecisionsActions";
import AddCreditCardButton from "@components/pages/wallet/AddCreditCardButton";

interface IProps {
  onChange: (message: string) => any;
  decision: DecisionsInterface;
}

const UnpaidOrder: React.FC<IProps> = (props: IProps) => {
  const dispatch = useDispatch();
  const { decision, onChange } = props;

  const classes = {
    p: [
      "estimate-table-caption",
      "estimate-table__caption",
      Styles.decisionCaption,
    ],
  };
  const initialState = {
    paymentMethod: "debit",
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

  async function submit(values: Record<any, any>, actions: Record<any, any>) {
    actions.setSubmitting(true);

    dispatch(
      solveDecisionAction({
        data: {
          decision_id: decision.decision_id,
          payment: "card",
          card_id: "1",
        },

        success() {
          actions.setSubmitting(false);
        },
      })
    );

    onChange("Decision solved");
  }

  function submitWithoutValidationOrder(
    payment: string,
    setSubmitting: (isSubmitting: boolean) => void
  ) {
    return async function () {
      setSubmitting(true);

      dispatch(
        solveDecisionAction({
          data: {
            decision_id: decision.decision_id,
            payment,
          },

          success() {
            setSubmitting(false);
          },
        })
      );

      onChange("Decision solved");
    };
  }

  return (
    <>
      <Formik
        initialValues={initialState}
        validationSchema={validationSchema}
        onSubmit={submit}
      >
        {({ values, handleChange, isSubmitting, setSubmitting }) => {
          console.log({ values });
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
                      <AddCreditCardButton classes={{ button: "w-auto" }} />
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
