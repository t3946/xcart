import * as React from "react";
import { HelpFormInputPropsDto } from "@/modules/shared/ts/types";
import ErrorMessageRight from "@/modules/shared/components/error-message-rigth/ErrorMessageRight";
import {
  InputClear,
  FormInput,
  ErrorMessageTop,
} from "@/modules/shared/components";
import { InputViewValid } from "../icons/input-view-valid/InputViewValid";

const HelpFormInput: React.FC<HelpFormInputPropsDto> = ({
  name,
  label,
  type = "text",
  required = false,
  error = false,
  errorMessage,
  clear,
  value,
  as = "",
  valid,
}) => {
  return (
    <div>
      <div className="formik-input-wrap">
        {error ? <ErrorMessageTop errorMessage={errorMessage} /> : null}
        <div className="formik-input-error-wrap">
          <div className="formik-input-button">
            <FormInput
              as={as}
              name={name}
              type={type}
              label={label}
              error={error}
              required={required}
              valid={valid}
            />
            {value.trim() ? (
              <span onClick={() => clear(name, "")}>
                <InputClear />
              </span>
            ) : null}
          </div>
          {error ? (
            <div className="error-message-right">
              <InputViewValid
                src={"/static/frontend/images/icons/forms/error.svg"}
              />
              <ErrorMessageRight errorMessage={errorMessage} />
            </div>
          ) : null}
          {valid ? (
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

export default HelpFormInput;
