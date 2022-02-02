import React from "react";
import cn from "classnames";

import Styles from "@modules/account/components/shared/CountInput.module.scss";

interface CountInputProps {
  value: number;
  onChange: (value: number, isInputEnter?: boolean) => void;
  onBlur: () => void;
  minAmount: number;
  multOrderQuantity: boolean;
  avail: number;
}

export const CountInput: React.FC<CountInputProps> = ({
  value,
  onChange,
  onBlur,
  minAmount,
  multOrderQuantity,
  avail,
}) => {
  return (
    <div className="d-flex">
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
        onChange={(e) => onChange(Number(e.target.value), true)}
        value={value}
        type={"number"}
        className={cn(Styles.input, "count-input")}
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
