import React from "react";
import RadioButtonDescription from "@modules/account/components/orders/Decision/UnpaidOrder/RadioButtonDescription";
import RadioButtonName from "@modules/account/components/orders/Decision/UnpaidOrder/RadioButtonName";
import cn from "classnames";
import Styles from "@modules/account/components/orders/Decision/UnpaidOrder/PaymentItem.module.scss";
import Accordion from "react-bootstrap/Accordion";

interface IProps {
  value: string;
  checkedValue: string;
  onChange: (e: any) => void;
  paymentName: string;
  caption: string;
  children?: React.ReactNode;
  fieldName: string;
  disabled: any;
  integrated?: boolean;
}

const PaymentItem: React.FC<IProps> = (props: IProps) => {
  const {
    value,
    checkedValue,
    onChange,
    paymentName,
    caption,
    children,
    fieldName,
    disabled,
    integrated,
  } = props;

  return (
    <div className={Styles.paymentItem}>
      <label
        className={cn([
          "align-items-center",
          "align-content-center",
          Styles.paymentItemGrid,
          Styles.paymentItemGrid_verticalLayout,
          {
            [Styles.cursorPointer]: checkedValue !== value && !disabled,
            [Styles.paymentItemGrid_integrated]: integrated,
          },
        ])}
      >
        <RadioButtonName
          name={fieldName}
          value={value}
          checkedValue={checkedValue}
          onChange={onChange}
          disabled={disabled}
        >
          <b>{paymentName}</b>
        </RadioButtonName>

        <p className={cn(["m-0", Styles.paymentItemCaption])}>{caption}</p>
      </label>

      <Accordion.Collapse eventKey={value}>
        <RadioButtonDescription integrated={integrated}>
          <div
            className={cn([
              "d-flex",
              "flex-dir-column",
              "align-items-center",
              "align-items-lg-start",
            ])}
          >
            {children}
          </div>
        </RadioButtonDescription>
      </Accordion.Collapse>
    </div>
  );
};

export default PaymentItem;
