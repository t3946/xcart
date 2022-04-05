import * as React from "react";
import PaymentSelection from "@modules/account/components/orders/Decision/UnpaidOrder/PaymentSelection";
import RadioSelectCard from "@modules/ui/RadioSelectCard";
import AddCreditCardButton from "@components/pages/wallet/AddCreditCardButton";
import Button from "@modules/ui/forms/Button";
import cn from "classnames";
import paymentItemStyles from "@modules/account/components/orders/Decision/UnpaidOrder/PaymentItem.module.scss";
import Styles from "@modules/account/components/orders/Decision/UnpaidOrder/UnpaidOrder.module.scss";

interface IProps {
  cards: any;
  defaultCardId: any;
  checkedValue: any;
  handleChange: any;
  isSubmitting: any;
  setSubmitting: any;
  submit: any;
  values: any;
  paypalUrl: any;
}

export const PaymentSections: React.FC<IProps> = function (props) {
  const {
    cards,
    defaultCardId,
    checkedValue,
    handleChange,
    isSubmitting,
    setSubmitting,
    submit,
    values,
    paypalUrl,
  } = props;

  return (
    <PaymentSelection
      checkedValue={checkedValue}
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
                onClick={submit(values, "pay-by-card", setSubmitting)}
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
                    onClick={submit(values, "pay-by-paypal", setSubmitting)}
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
};

export default PaymentSections;
