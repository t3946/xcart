import cn from "classnames";
import Styles from "@modules/account/components/orders/Decision/UnpaidOrder/RadioButtonName.module.scss";
import RadioButton from "@modules/account/components/orders/Decision/RadioButton";
import React from "react";

interface IProps {
  value: string;
  checkedValue: string;
  onChange: (e) => void;
  children: React.ReactNode;
}
const RadioButtonName = (props: IProps) => {
  const { value, checkedValue, onChange, children } = props;

  return (
    <div
      className={cn(["d-flex align-items-center", Styles.paymentItemName])}
    >
      <RadioButton
        name={"payment"}
        value={value}
        checkedValue={checkedValue}
        disabled={false}
        onChange={onChange}
      />

      {children}
    </div>
  );
};

export default RadioButtonName;
