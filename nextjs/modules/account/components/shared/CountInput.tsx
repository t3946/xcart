import React from "react";
import cn from "classnames";

import Styles from "@modules/account/components/shared/CountInput.module.scss";

interface IProps {
  value: number;
  onChange: (value: number, isInputEnter?: boolean) => void;
  onBlur?: () => void;
  minAmount: number;
  multOrderQuantity: boolean;
  avail: number;
  className: any;
}

export const CountInput: React.FC<IProps> = (props) => {
  const {
    value,
    onChange,
    onBlur,
    minAmount,
    multOrderQuantity,
    avail,
    className,
  } = props;

  return (
    <div className={cn("d-flex", className)}>
      <div
        onClick={() => onChange(value - (multOrderQuantity ? minAmount : 1))}
        className={cn(
          Styles.button,
          "count-input-btn",
          "count-input-btn__left",
          { [Styles.button_disable]: value === minAmount }
        )}
      >
        -
      </div>

      <input
        onChange={(e) => {
          onChange(parseInt(e.target.value) || minAmount, true);
        }}
        value={value}
        className={cn(Styles.input, "count-input", "flex-grow-1")}
        onBlur={onBlur}
        max={avail}
      />

      <div
        onClick={() => onChange(value + (multOrderQuantity ? minAmount : 1))}
        className={cn(
          Styles.button,
          "count-input-btn",
          "count-input-btn__right",
          { [Styles.button_disable]: value === avail }
        )}
      >
        +
      </div>
    </div>
  );
};

export default CountInput;
