import React from "react";
import PaymentSelection from "@modules/account/components/orders/Decision/UnpaidOrder/PaymentSelection";
import Styles from "@modules/account/components/orders/Decision/UnpaidOrder/UnpaidOrder.module.scss";
import cn from "classnames";
import DecisionsInterface from "@modules/account/ts/types/decision";
interface IProps {
  onChangeDecision: (decision: DecisionsInterface) => any;
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
    paymentSelection: Styles.decision__paymentSelection,
  };

  const [activeRadioButton, setActiveRadioButton] =
    React.useState<string>("debit");

  const handleChangeRadioButton = (e) => {
    setActiveRadioButton(e.target.value);
  };
  return (
    <>
      <h1 className={cn(["decision-inner-header", Styles.decision__title])}>
        Unpaid order
      </h1>
      <p className={cn([classes.p, Styles.decision__caption_lineIndent])}>
        We received your order but it hasn't been paid for yet.
      </p>
      <p className={cn([classes.p, Styles.decision__caption_paragraphIndent])}>
        Please pay for the order so that we can process it.
      </p>
      <PaymentSelection
        checkedValue={activeRadioButton}
        onChange={handleChangeRadioButton}
        decision={decision}
        onChangeDecision={onChangeDecision}
        classes={classes.paymentSelection}
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
          type={"button"}
          className={cn([
            "form-button",
            "form-button__outline",
            "fw-bold",
            "mt-4",
            Styles.button,
            Styles.decision__button,
            Styles.decision__button_cancelOrder,
          ])}
        >
          Cancel order
        </button>
      </div>
    </>
  );
};

export default UnpaidOrder;
