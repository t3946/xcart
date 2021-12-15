import React, { ChangeEvent, FocusEventHandler, MutableRefObject } from "react";
import classnames from "classnames";
import { FormikErrors, FormikTouched } from "formik";
import InputMask from "react-input-mask";
import Styles from "@modules/account/components/shared/FormInput.module.scss";
interface FormInputPropsDto {
  label?: string;
  placeholder?: string;
  name: string;
  id?: string;
  type?: string;
  errorMessage?: string | FormikErrors<any> | string[] | FormikErrors<any>[];
  handleChange: (e: string | ChangeEvent<any>) => void;
  classes?: {
    group?: string | string[] | null;
    grid?: string | string[] | null;
    label?: string | string[] | null;
    input?: string | string[] | null;
    textArea?: string | string[] | null;
  };
  value: any;
  touched?: boolean | FormikTouched<any> | FormikTouched<any>[];
  handleBlur?: FocusEventHandler<HTMLInputElement | HTMLTextAreaElement>;
  mask?: string;
  inputType?: string;
  autoFocus?: boolean;
  inputRef?: MutableRefObject<any>;
}

export const FormInput: React.FC<FormInputPropsDto> = ({
  label,
  placeholder,
  classes,
  name,
  id,
  type,
  errorMessage,
  handleChange,
  value,
  touched,
  handleBlur,
  mask,
  inputType = "input",
  autoFocus,
  inputRef,
}) => {
  const error = errorMessage && touched;
  return (
    <div className={classnames("form-input-container", classes?.group)}>
      <div
        className={classnames(
          "d-flex",
          "flex-wrap",
          "align-items-center",
          Styles.grid,
          classes?.grid
        )}
      >
        {label && (
          <label
            htmlFor={id}
            className={classnames(
              "form-input-label",
              classes?.label,
              `${error && "form-input-label-error"}`
            )}
          >
            {label}
          </label>
        )}
        <div className={classnames(classes?.input)}>
          {inputType === "input" ? (
            <InputMask
              mask={mask}
              value={value}
              onChange={handleChange}
              onBlur={handleBlur}
            >
              {(inputProps) => (
                <input
                  ref={inputRef}
                  autoFocus={autoFocus}
                  onBlur={handleBlur}
                  placeholder={placeholder}
                  className={classnames(
                    "form-input",
                    `${error && "form-input_error"}`
                  )}
                  name={name}
                  id={id}
                  type={type ? type : "text"}
                  onChange={handleChange}
                  value={value}
                />
              )}
            </InputMask>
          ) : (
            <textarea
              onBlur={handleBlur}
              placeholder={placeholder}
              className={classnames(
                "form-input",
                `${error && "form-input_error"}`,
                classes?.textArea
              )}
              name={name}
              id={id}
              onChange={handleChange}
              value={value}
              ref={inputRef}
            />
          )}
        </div>
      </div>
      <div className="error-message-input-container">
        <div className={classnames(classes?.input)}>
          {error && <div className="form-input-caption">{errorMessage}</div>}
        </div>
      </div>
    </div>
  );
};
