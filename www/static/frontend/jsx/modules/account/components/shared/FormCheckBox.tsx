import React from "react";

export const FormCheckBox = ({ name, label, value }) => {
  return (
    <div className="form-checkbox-container">
      <input
        className="form-checkbox"
        id="styled-checkbox-2"
        type="checkbox"
        value={value}
      />
      <label className={"checkbox-label"} htmlFor="styled-checkbox-2">
        <div className={"label-text"}>{label}</div>
      </label>
    </div>
  );
};
