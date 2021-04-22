import React from "react";
import { ErrorMessageDto } from "@/modules/shared/ts/types";

export const ErrorMessageTop: React.FC<ErrorMessageDto> = ({
  errorMessage,
}) => {
  return (
    <div className="error-top-wrapper">
      <div className="common-field-error-wrapper">
        <ul className="error-top errors form-field-error form-field__error checkout__error error_checkout common-field-error_visible">
          <li className="form-field-error-text">{errorMessage}</li>
        </ul>
      </div>
    </div>
  );
};
