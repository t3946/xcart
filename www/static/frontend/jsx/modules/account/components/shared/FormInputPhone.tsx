import React from "react";
import { Form as RBForm } from "react-bootstrap";
import { FormSelect } from "@client/modules/account/components/shared/FormSelect";
import { useSelector } from "react-redux";
import { AccountStore } from "@client/modules/account/ts/types/account-store.type";
import { getCountryByCode } from "@client/jsx/utils/Countries";
import useBreakpoint from "@client/modules/account/hooks/useBreakpoint";
import classnames from "classnames";
import InputMask from "react-input-mask";

interface PropsInterface {
  handleChange: () => any;
  setFieldValue: (string, any) => void;
  touched: Record<any, any>;
  errors: Record<any, any>;
  name: string;
  countryCodeValue?: any;
  initialPhoneValue?: string;
  values: {
    countryCode?: string; // RU, AU etc
    phone: string; // phone without counter code
    phoneExt?: string; // external phone code
  };
  mode?: string; // mobile or ext
  label: string;
}

const FormInputPhone: React.FC<any> = function (props: PropsInterface) {
  const {
    setFieldValue,
    handleChange,
    touched,
    errors,
    name,
    values,
    mode,
    label,
  } = props;
  const countries = useSelector((e: AccountStore) => e.countries);
  const countryCodeFieldName = name + "CountryCode";
  const phoneExtFieldName = name + "Ext";
  const phoneMask = "(999) 999-9999";

  const breakpoint = useBreakpoint();

  let initialCountryCode;

  if (values.countryCode) {
    const country = getCountryByCode(values.countryCode, countries);

    initialCountryCode = {
      viewValue: country.name + " +" + country.phone_code,
      previewValue: country.code + " +" + country.phone_code,
      value: country.code,
    };
  } else {
    initialCountryCode = { viewValue: "Code" };
  }

  const [countryCodeValue, setCountryCodeValue] =
    React.useState(initialCountryCode);

  /**
   * Get countries list for select input
   */
  function getSelectItems() {
    const codes = [];

    for (const country of countries) {
      if (country.phone_code) {
        codes.push({
          viewValue: country.name + " +" + country.phone_code,
          previewValue: country.code + " +" + country.phone_code,
          value: country.code,
        });
      }
    }

    return codes;
  }

  const classes = {
    selectCountryCodeColumn: ["col pe-0 phone-country-code-column"],
    inputPhoneColumn: [],
    inputPhoneExt: ["col phone-ext-column d-flex ps-0 align-items-center"],
  };

  if (mode === "mobile") {
    classes.selectCountryCodeColumn.push("mb-2 mb-md-0");
    classes.inputPhoneColumn.push("col");
    classes.inputPhoneExt.push("d-none");
  } else if (mode === "ext") {
    classes.selectCountryCodeColumn.push("d-none");
    classes.inputPhoneColumn.push("col");
    classes.inputPhoneExt.push("d-lg-flex");
  }

  return (
    <RBForm.Group controlId={name} className={"row"}>
      <div className={"col-12 col-md-6 col-lg-3 label-column"}>
        <RBForm.Label className={"form-input-label mb-1 mb-md-0"}>
          {label}
        </RBForm.Label>
      </div>

      <div className="col">
        <div className="row">
          <div className={classnames(classes.selectCountryCodeColumn)}>
            <FormSelect
              items={getSelectItems()}
              classes={{ selectList: "form-select-list__fit-content" }}
              value={countryCodeValue}
              onClick={(item) => {
                setFieldValue(countryCodeFieldName, item.value);
                setCountryCodeValue(item);
              }}
              name={countryCodeFieldName}
              id={countryCodeFieldName}
            />
          </div>

          <div className={classnames(classes.inputPhoneColumn)}>
            <InputMask
              mask={phoneMask}
              value={values[name]}
              onChange={handleChange}
            >
              {() => (
                <input
                  placeholder="(___) ___-____"
                  className={classnames("form-input", {
                    "form-input-error": !!errors[name],
                  })}
                  name={name}
                  id={name}
                  type="text"
                  onChange={handleChange}
                  value={values[name]}
                />
              )}
            </InputMask>

            <RBForm.Control.Feedback type="invalid">
              {errors[name]}
            </RBForm.Control.Feedback>
          </div>

          <div className={classnames(classes.inputPhoneExt)}>
            <RBForm.Label className={"form-input-label mb-0 me-2 fw-normal"}>
              {breakpoint({
                xs: "X",
                md: "ext",
              })}
            </RBForm.Label>

            <RBForm.Control
              type="text"
              name={phoneExtFieldName}
              value={values[phoneExtFieldName]}
              onChange={handleChange}
              className={"form-input"}
              isInvalid={
                !!touched[phoneExtFieldName] && !!errors[phoneExtFieldName]
              }
              isValid={touched[phoneExtFieldName] && !errors[phoneExtFieldName]}
              autoComplete={"off"}
            />

            <RBForm.Control.Feedback type="invalid">
              {errors[phoneExtFieldName]}
            </RBForm.Control.Feedback>
          </div>
        </div>
      </div>
    </RBForm.Group>
  );
};

export default FormInputPhone;
