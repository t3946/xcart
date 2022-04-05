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
import RadioSelectCard from "@modules/ui/RadioSelectCard";
import Button, { ETheme } from "@modules/ui/forms/Button";
import CardHeader from "@modules/account/components/wallet/CardHeader";

interface IProps {
  onChange: (message: string) => any;
  decision: DecisionsInterface;
  paypalUrl: string;
}

const UnpaidOrder: React.FC<IProps> = (props: IProps) => {
  const dispatch = useDispatch();
  const { decision, onChange, cards, defaultCardId, paypalUrl } = props;
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
    cardId: defaultCardId,
  };
  const validationSchema = yup.object().shape({
    paymentMethod: yup.string(),
    cardholderName: yup
      .string()
      .max(40, "Max length is 40 character")
      .min(2, "Min length is 2 character")
      .required("Cardholder name is a required field"),
  });

  function submitWithoutValidationOrder(
    values,
    action: string,
    setSubmitting: (isSubmitting: boolean) => void
  ) {
    return async function () {
      setSubmitting(true);

      dispatch(
        solveDecisionAction({
          data: {
            decision_id: decision.decision_id,
            cardId: values.cardId,
            action,
          },

          success() {
            setSubmitting(false);
          },
        })
      );

      onChange("Decision solved");
    };
  }

  function paymentSectionTemplate(
    values,
    handleChange,
    isSubmitting,
    setSubmitting
  ) {
    if (decision.solved) {
      switch (decision.options.action) {
        case "pay-by-card":
          const card = decision.options.card;
          return (
            <>
              <p className={cn([classes.p, Styles.widthMaxContent])}>
                Paid by credit card.
              </p>
              <div className={Styles.decisionCaption}>
                <CardHeader cardLast4={card.last4} cardType={card.brand} />
              </div>
            </>
          );
        case "pay-by-paypal":
          return (
            <p className={cn([classes.p, "m-0", Styles.widthMaxContent])}>
              Paid by paypal.
            </p>
          );
        case "cancel-order":
          return null;
      }
      return null;
    }

    return (
      <PaymentSelection
        //for formik
        checkedValue={values.paymentMethod}
        onChange={handleChange}
        disabled={isSubmitting}
        name="paymentMethod"
        options={[
          {
            label: "Pay by Credit / Debit card, Apple Pay and Google Pay",
            caption:
              "Secure Visa, MasterCard, and AmEx payment through our secure server.",
            value: "debit",
            template: (
              <>
                <div className={"mb-3"}>
                  <p className={"mb-2"}>
                    <b>Select card:</b>
                  </p>

                  <RadioSelectCard
                    name={"cardId"}
                    cards={cards}
                    checkedValue={values.cardId}
                    defaultCardId={defaultCardId}
                    onChange={handleChange}
                  />
                </div>

                <AddCreditCardButton classes={{ button: "w-auto" }} />

                <Button
                  type={"button"}
                  onClick={submitWithoutValidationOrder(
                    values,
                    "pay-by-card",
                    setSubmitting
                  )}
                  className={"w-auto mt-3"}
                >
                  Pay by card
                </Button>
              </>
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
                  You will be transferred to PayPal website to complete your
                  payment.
                </p>

                <div
                  className={
                    "d-flex justify-content-center justify-content-lg-start"
                  }
                >
                  <a href={paypalUrl} className={"text-decoration-none"}>
                    <Button
                      type={"button"}
                      disabled={isSubmitting}
                      onClick={submitWithoutValidationOrder(
                        values,
                        "pay-by-paypal",
                        setSubmitting
                      )}
                      className={cn(["form-button", Styles.button])}
                    >
                      Pay by PayPal
                    </Button>
                  </a>
                </div>
              </div>
            ),
          },
        ]}
      />
    );
  }

  function cancelOrderTemplate(values, isSubmitting, setSubmitting) {
    if (decision.solved) {
      if (decision.options.action === "cancel-order") {
        return (
          <p className={cn([classes.p, "m-0", Styles.widthMaxContent])}>
            Order was cancelled.
          </p>
        );
      } else {
        return null;
      }
    }

    return (
      <>
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
          <Button
            type="button"
            onClick={submitWithoutValidationOrder(
              values,
              "cancel-order",
              setSubmitting
            )}
            className={cn([
              "fw-bold",
              "mt-4",
              Styles.button,
              Styles.decision__button,
              Styles.decision__button_cancelOrder,
            ])}
            theme={ETheme.outlined}
            disabled={isSubmitting}
          >
            Cancel order
          </Button>
        </div>
      </>
    );
  }

  return (
    <>
      <Formik initialValues={initialState} validationSchema={validationSchema}>
        {({ values, handleChange, isSubmitting, setSubmitting }) => {
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

              {!decision.solved && (
                <>
                  <p
                    className={cn([
                      classes.p,
                      Styles.decision__caption_lineIndent,
                    ])}
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
                </>
              )}

              {paymentSectionTemplate(
                values,
                handleChange,
                isSubmitting,
                setSubmitting
              )}

              {cancelOrderTemplate(values, isSubmitting, setSubmitting)}
            </Form>
          );
        }}
      </Formik>
    </>
  );
};

export default UnpaidOrder;
