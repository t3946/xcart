import React from "react";
import cn from "classnames";
import CountInput from "@modules/account/components/shared/CountInput";

import Styles from "@modules/account/components/shared/CountGroup.module.scss";

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

      <CountInput
        minAmount={multOrderQuantity ? minAmount : 1}
        onChange={onChange}
        value={value}
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
