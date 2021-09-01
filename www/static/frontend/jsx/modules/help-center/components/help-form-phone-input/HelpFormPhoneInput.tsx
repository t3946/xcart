import * as React from "react";
import { HelpFormPhoneInputPropsDto } from "@/modules/shared/ts/types";
import ErrorMessageRight from "@/modules/shared/components/error-message-rigth/ErrorMessageRight";
import {
  ErrorMessageTop,
  FormInput,
  InputClear,
} from "@/modules/shared/components";
import { InputViewValid } from "@/modules/shared/components/icons/input-view-valid/InputViewValid";

const HelpFormPhoneInput: React.FC<HelpFormPhoneInputPropsDto> = ({
  name,
  label,
  type = "text",
  required = false,
  error = false,
  errorMessage,
  clear,
  value,
  valueExt,
  as = "",
  extName,
  errorExt,
  valid,
  validExt,
}) => {
  return (
    <div>
      <div className="formik-input-wrap">
        {error || errorExt ? (
          <ErrorMessageTop errorMessage={errorMessage} />
        ) : null}
        <div className="formik-input-error-wrap">
          <div className="phone-input-wrap">
            <div className="formik-input-button phone-input-left">
              <FormInput
                valid={valid}
                as={as}
                name={name}
                type={type}
                label={label}
                error={error}
                required={required}
              />
              {value.trim() ? (
                <p onClick={() => clear(name, "")}>
                  <InputClear />
                </p>
              ) : null}
            </div>
            <div className="formik-input-button phone-input-right">
              <span className="phone-input-right-text">ext</span>
              <FormInput
                valid={validExt}
                as={as}
                name={extName}
                type={type}
                label={label}
                error={errorExt}
                required={required}
              />
              {valueExt.trim() ? (
                <p onClick={() => clear(extName, "")}>
                  <InputClear />
                </p>
              ) : null}
            </div>
          </div>

          {error || errorExt ? (
            <div className="error-message-right">
              <InputViewValid
                src={"/static/frontend/images/icons/forms/error.svg"}
              />
              <ErrorMessageRight errorMessage={errorMessage} />
            </div>
          ) : null}
          {valid && validExt ? (
            <div className="error-message-right">
              <InputViewValid
                src={
                  "/static/frontend/images/icons/forms/checkmark_accepted.svg"
                }
              />
            </div>
          ) : null}
        </div>
      </div>
    </div>
  );
};

export default HelpFormPhoneInput;
