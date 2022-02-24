import React from "react";

export const FormCheckBox = ({
  name,
  label,
  value,
  handleChange,
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
      />
      <label className={"checkbox-label"} htmlFor={id}>
        <div className={"label-text"}>{label}</div>
      </label>
    </div>
  );
};
