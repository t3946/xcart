import React from "react";
import cn from "classnames";
import CountInput from "@modules/ui/CountInput";

import Styles from "@modules/ui/CountGroup.module.scss";

interface CountInputProps {
  value: number;
  onChange: (value: number, isInputEnter?: boolean) => void;
  onBlur: () => void;
  minAmount: number;
  multOrderQuantity: boolean;
  avail: number;
}

export const CountGroup: React.FC<CountInputProps> = ({
  value,
  onChange,
  onBlur,
  minAmount,
  multOrderQuantity,
  avail,
}) => {
  return (
    <div className="d-flex">
      <button
        onClick={() => onChange(value - (multOrderQuantity ? minAmount : 1))}
        className={cn(
          Styles.button,
          "count-input-btn",
          "text-center",
          "fw-bold",
          "count-input-btn__left",
          { [Styles.button_disable]: value === minAmount }
        )}
        disabled={value === minAmount}
      >
        -
      </button>

      <CountInput
        minAmount={multOrderQuantity ? minAmount : 1}
        onChange={onChange}
        value={value}
        onBlur={onBlur}
        max={avail}
      />

      <button
        disabled={value === avail}
        onClick={() => onChange(value + (multOrderQuantity ? minAmount : 1))}
        className={cn(
          Styles.button,
          "count-input-btn",
          "text-center",
          "fw-bold",
          "count-input-btn__right",
          { [Styles.button_disable]: value === avail }
        )}
      >
        +
      </button>
    </div>
  );
};
