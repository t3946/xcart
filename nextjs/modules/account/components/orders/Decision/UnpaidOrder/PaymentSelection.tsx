import React from "react";
import cn from "classnames";
import PaymentItem from "@modules/account/components/orders/Decision/UnpaidOrder/PaymentItem";
import PayByCardForm from "@modules/account/components/orders/Decision/UnpaidOrder/PayByCardForm";
import { Accordion } from "react-bootstrap";
import DecisionsInterface from "@modules/account/ts/types/decision";
import unpaidOrderStyles from "@modules/account/components/orders/Decision/UnpaidOrder/UnpaidOrder.module.scss";
import paymentItemStyles from "@modules/account/components/orders/Decision/UnpaidOrder/PaymentItem.module.scss";
import Styles from "@modules/account/components/orders/Decision/UnpaidOrder/PaymentSelection.module.scss";

interface IProps {
  checkedValue: string;
  fieldName: string;
  onChange: (e: any) => void;
  decision: DecisionsInterface;
  classes?: any;
  isSubmitting: boolean;
  errors: Record<any, any>;
  values: Record<any, any>;
  touched: Record<any, any>;
  setCreatePaymentMethod: any;
}

const PaymentSelection: React.FC<IProps> = (props: IProps) => {
  const {
    checkedValue,
    onChange,
    fieldName,
    isSubmitting,
    errors,
    values,
    touched,
    setCreatePaymentMethod,
  } = props;
  const classes = [Styles.paymentSelector, props.classes];

  return (
    <Accordion
      className={cn(["mb-4", "mb-md-5", classes])}
      activeKey={checkedValue}
    >
      <PaymentItem
        value={"debit"}
        checkedValue={checkedValue}
        onChange={onChange}
        paymentName={"Pay by Credit / Debit card, Apple Pay and Google Pay"}
        caption={
          "Secure Visa, MasterCard, and AmEx payment through our secure server."
        }
        fieldName={fieldName}
      >
        <PayByCardForm
          isSubmitting={isSubmitting}
          errors={errors}
          values={values}
          touched={touched}
          onChange={onChange}
          setCreatePaymentMethod={setCreatePaymentMethod}
        />
      </PaymentItem>

      <PaymentItem
        value={"paypal"}
        fieldName={fieldName}
        checkedValue={checkedValue}
        onChange={onChange}
        paymentName={"Pay by PayPal Balance"}
        caption={
          "Secure payment by PayPal Balance (click Create an Account to also access VISA, MC, AmEx, and Discover payments)."
        }
      >
        <div className="w-100">
          <p
            className={cn([
              paymentItemStyles.paymentItemCaption,
              paymentItemStyles.paymentItemCaption_accent,
            ])}
          >
            You will be transferred to PayPal website to complete your payment.
          </p>

          <div
            className={"d-flex justify-content-center justify-content-lg-start"}
          >
            <button
              type={"button"}
              className={cn([
                "form-button",
                unpaidOrderStyles.button,
                unpaidOrderStyles.decision__button,
              ])}
            >
              Pay by PayPal
            </button>
          </div>
        </div>
      </PaymentItem>
    </Accordion>
  );
};

export default PaymentSelection;
