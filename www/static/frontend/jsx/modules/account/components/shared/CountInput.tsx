import React from "react";

interface CountInputProps {
  value: number;
  onChange: (value: number) => void;
}

export const CountInput: React.FC<CountInputProps> = ({ value, onChange }) => {
  return (
    <div className="d-flex">
      <div
        onClick={() => onChange(value - 1)}
        className="count-input-btn count-input-btn__left"
      >
        -
      </div>
      <input
        disabled
        onChange={(e) => onChange(e.target.value)}
        value={value}
        type={"number"}
        className="count-input"
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
