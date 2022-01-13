import React from "react";
import cn from "classnames";
import PaymentItem from "@modules/account/components/orders/Decision/UnpaidOrder/PaymentItem";
import { Accordion } from "react-bootstrap";
import Styles from "@modules/account/components/orders/Decision/UnpaidOrder/PaymentSelection.module.scss";

export interface IAccordionItem {
  value: any;
  label: any;
  caption: any;
  template: any;
}

interface IProps {
  checkedValue: string;
  name: string;
  onChange: (e: any) => void;
  disabled: any;
  classes?: any;
  options: IAccordionItem[];
  integrated?: boolean;
}

const PaymentSelection: React.FC<IProps> = (props: IProps) => {
  const { checkedValue, name, onChange, disabled, options, integrated } = props;
  const classes = [Styles.paymentSelector, props.classes];

  return (
    <Accordion
      className={cn(["mb-4", "mb-md-5", classes])}
      activeKey={checkedValue}
    >
      {options.map((option, index) => (
        <PaymentItem
          key={`${index}_${option.label}`}
          value={option.value}
          checkedValue={checkedValue}
          onChange={onChange}
          paymentName={option.label}
          caption={option.caption}
          fieldName={name}
          disabled={disabled}
          integrated={integrated}
        >
          {option.template}
        </PaymentItem>
      ))}
    </Accordion>
  );
};

export default PaymentSelection;
