import React from "react";

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
        className="count-input-btn count-input-btn__left"
      >
        -
      </div>
      <input
        onChange={(e) => onChange(Number(e.target.value), true)}
        value={value}
        type={"number"}
        className="count-input"
        onBlur={onBlur}
        max={avail}
      />
      <div
        onClick={() => onChange(value + (multOrderQuantity ? minAmount : 1))}
        className="count-input-btn count-input-btn__right"
      >
        +
      </div>
    </div>
  );
};
