import React from "react";

export const FormCheckBox = ({
  name,
  label,
  value,
  handleChange,
  disabled,
  id = "styled-checkbox-2 ",
}) => {
  return (
    <div className="form-checkbox-container">
      <input
        className="form-checkbox"
        id={id}
        type="checkbox"
        value={value}
        name={name}
        onChange={handleChange}
        checked={value}
        disabled={disabled}
      />
      <label htmlFor={id} className={"checkbox-label"}>
        <div className={"label-text text-dark"}>{label}</div>
      </label>
    </div>
  );
};
