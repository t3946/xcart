import React from "react";
import { ErrorMessageDto } from "@/modules/shared/ts/types";

const ErrorMessageRight: React.FC<ErrorMessageDto> = ({ errorMessage }) => {
  return (
    <div className="show-for-large large-errors-content">
      <ul
        id="ShippingForm_s_firstname_errors"
        className="form-field-error common-field-error_visible error-right error-right-margin"
      >
        <li className="error-right">{errorMessage}</li>
      </ul>
    </div>
  );
};

export default ErrorMessageRight;
