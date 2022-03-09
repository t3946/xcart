import React from "react";
import { Form as RBForm } from "react-bootstrap";
import Select from "@modules/ui/forms/select/Select";
import { useSelector } from "react-redux";
import StoreInterface from "@modules/account/ts/types/store.type";
import { getCountryByCode } from "@utils/Countries";
import classnames from "classnames";
import Label from "@modules/ui/forms/Label";
import Input from "@modules/ui/forms/Input";
import MaskedInput from "@modules/ui/forms/MaskedInput";
import Feedback from "@modules/ui/forms/Feedback";
import * as Yup from "yup";

import Styles from "@modules/account/components/shared/FormInputPhone.module.scss";

export const phoneYupValidation = Yup.string()
  .required("Required field")
  .matches(/[(]\d{3}[)] \d{3}[-]\d{4}/, "Is not in correct format");

export const phoneExtYupValidation = Yup.string()
  .nullable()
  .max(5, "The maximum number of characters is 5");

interface IProps {
  handleChange: () => any;
  setFieldValue: (arg0: string, arg1: any) => void;
  touched: Record<any, any>;
  errors: Record<any, any>;
  name: string;
  countryCodeValue?: any;
  initialPhoneValue?: string;
  values: Record<string, any>;
  disabled?: boolean;
  mode?: string; // mobile or ext
  classes?: {
    container?: any;
    labelExt?: any;
    select?: any;
    phone?: any;
    ext?: any;
  };
}

const FormInputPhone: React.FC<any> = function (props: IProps) {
  const {
    setFieldValue,
    handleChange,
    touched,
    errors,
    name,
    values,
    mode,
    disabled,
  } = props;
  const countries = useSelector((e: StoreInterface) => e.countries);
  const CodeFieldName = name + "Code";
  const ExtFieldName = "phone_ext";
  const phoneMask = "(999) 999-9999";
  const placeholder = "(___) ___-____";
  let initialCountryCode;

  if (values[CodeFieldName]) {
    const country = getCountryByCode(values[CodeFieldName], countries);

    initialCountryCode = {
      label: country.code + " +" + country.phone_code,
      value: country.code,
    };
  } else {
    initialCountryCode = { label: "Code", value: "" };
  }
  /**
   * Get countries list for select input
   */
  function getSelectItems(): any {
    const codes = [];
    const whiteListCodes = ["US", "CA"];

    if (!countries) {
      return codes;
    }

    for (const country of countries) {
      if (whiteListCodes.indexOf(country.code) === -1) {
        continue;
      }

      if (country.phone_code) {
        codes.push({
          label: country.code + " +" + country.phone_code,
          prelabel: country.code + " +" + country.phone_code,
          value: country.code,
        });
      }
    }

    return codes;
  }

  const classes = {
    container: [
      "row",
      "flex-md-nowrap",
      Styles.row__container,
      props.classes?.container,
    ],
    selectCountryCodeColumn: ["px-0", Styles.select, props.classes?.select],
    inputPhoneColumn: ["pe-2", props.classes?.phone, Styles.phone],
    inputPhoneExt: ["col d-flex px-0 align-items-center", props.classes?.ext],
    labelExt: props.classes?.labelExt,
  };

  if (mode === "mobile") {
    classes.selectCountryCodeColumn.push("mb-2 mb-md-0");
    classes.inputPhoneColumn.push("ps-2");
    classes.inputPhoneExt.push("d-none");
  } else if (mode === "ext") {
    classes.selectCountryCodeColumn.push("mb-2 mb-md-0");
    classes.inputPhoneColumn.push("ps-0 ps-md-2");
    classes.inputPhoneExt.push("d-lg-flex");
  }

  return (
    <div className={classnames(classes.container)}>
      <div className={classnames(classes.selectCountryCodeColumn)}>
        <Select
          clearable={false}
          classes={{ indicatorSeparator: "d-none", valueContainer: "ps-0" }}
          options={getSelectItems()}
          value={initialCountryCode}
          disabled={disabled}
          onChange={(e) => {
            setFieldValue(CodeFieldName, e.target.value.value);
          }}
          name={CodeFieldName}
          isValid={!!touched[CodeFieldName] && !errors[CodeFieldName]}
          isInvalid={!!(touched[CodeFieldName] && errors[CodeFieldName])}
        />
      </div>

      <div className={classnames(classes.inputPhoneColumn)}>
        <RBForm.Group controlId={name}>
          <MaskedInput
            type={"text"}
            name={name}
            value={values[name]}
            onChange={handleChange}
            placeholder={placeholder}
            isInvalid={!!touched[name] && !!errors[name]}
            isValid={!!touched[name] && !errors[name]}
            mask={phoneMask}
            disabled={disabled}
          />

          {((!!touched[name] && !!errors[name]) ||
            (!!touched[ExtFieldName] && !!errors[ExtFieldName])) && (
            <Feedback className="position-absolute d-block" type="invalid">
              {errors[name] || errors[ExtFieldName]}
            </Feedback>
          )}
        </RBForm.Group>
      </div>

      <RBForm.Group
        className={classnames(classes.inputPhoneExt)}
        controlId={ExtFieldName}
      >
        <Label
          className={classnames(
            "mb-0 me-2",
            Styles.label_ext,
            classes.labelExt
          )}
        >
          <span className="d-md-none">x</span>
          <span className="d-none d-md-inline">ext</span>
        </Label>

        <Input
          type="text"
          name={ExtFieldName}
          value={values[ExtFieldName]}
          onChange={handleChange}
          disabled={disabled}
          autoComplete={"off"}
          placeholder="12345"
          isValid={!!(touched[ExtFieldName] && !errors[ExtFieldName])}
          isInvalid={!!(touched[ExtFieldName] && errors[ExtFieldName])}
        />
      </RBForm.Group>
    </div>
  );
};

export default FormInputPhone;
