import React from "react";

export const InputClear: React.FC = () => {
  return (
    <img
      src={"/static/frontend/images/icons/forms/remove.svg"}
      className="formik-input-suffix-btn"
      aria-hidden="true"
    />
  );
};

export default InputClear;
