import React from "react";
import cn from "classnames";
import Styles from "@modules/ui/CountInput.module.scss";

interface IProps {
  minAmount: number;
  max?: number;
  value: number;
  onChange: (quantity: number, isInputEnter: boolean) => void;
  onBlur?: (e: React.SyntheticEvent<HTMLInputElement>) => void;
  name?: string;
  disabled?: boolean;
}

const CountInput: React.FC<IProps> = ({
  minAmount,
  onChange,
  onBlur,
  max,
  value,
  name,
  disabled,
}) => {
  return (
    <input
      name={name}
      onChange={(e) => {
        onChange(Math.abs(parseInt(e.target.value)) || minAmount, true);
      }}
      value={value}
      className={cn(Styles.input, "count-input")}
      onBlur={onBlur}
      min={minAmount}
      max={max}
      disabled={disabled}
    />
  );
};

export default CountInput;
