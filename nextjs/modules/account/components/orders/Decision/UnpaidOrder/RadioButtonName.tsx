import cn from "classnames";
import Styles from "@modules/account/components/orders/Decision/UnpaidOrder/RadioButtonName.module.scss";
import RadioButton from "@modules/ui/RadioButton";
import React from "react";

interface IProps {
  value: string;
  checkedValue: string;
  onChange: (e: any) => void;
  children: React.ReactNode;
  name: string;
  disabled: any;
}
const RadioButtonName = (props: IProps) => {
  const { value, checkedValue, onChange, children, name, disabled } = props;

  return (
    <div className={cn(["d-flex align-items-center", Styles.paymentItemName])}>
      <RadioButton
        name={name}
        value={value}
        checkedValue={checkedValue}
        disabled={disabled}
        onChange={onChange}
      />

      {children}
    </div>
  );
};

export default RadioButtonName;
