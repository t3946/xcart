import React from "react";

interface CountInputProps {
  value: number;
  onChange: (value: number, isInputEnter?: boolean) => void;
  onBlur: () => void;
}

export const CountInput: React.FC<CountInputProps> = ({
  value,
  onChange,
  onBlur,
}) => {
  return (
    <div className="d-flex">
      <div
        onClick={() => onChange(value - 1)}
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
      />
      <div
        onClick={() => onChange(value + 1)}
        className="count-input-btn count-input-btn__right"
      >
        +
      </div>
    </div>
  );
};
