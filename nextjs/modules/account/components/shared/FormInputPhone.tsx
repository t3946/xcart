import React from "react";
import { Form as RBForm } from "react-bootstrap";
import FormSelect from "@modules/ui/forms/Select";
import { useSelector } from "react-redux";
import StoreInterface from "@modules/account/ts/types/store.type";
import { getCountryByCode } from "@utils/Countries";
import classnames from "classnames";
import Label from "@modules/ui/forms/Label";
import Input from "@modules/ui/forms/Input";
import MaskedInput from "@modules/ui/forms/MaskedInput";
import Feedback from "@modules/ui/forms/Feedback";
import classNames from "classnames";

import Styles from "@modules/account/components/shared/FormInputPhone.module.scss";

interface IProps {
  handleChange: () => any;
  setFieldValue: (arg0: string, arg1: any) => void;
  touched: Record<any, any>;
  errors: Record<any, any>;
  name: string;
  countryCodeValue?: any;
  initialPhoneValue?: string;
  values: {
    phoneCountryCode?: string; // RU, AU etc
    phone: string; // phone without counter code
    phoneExt?: string; // external phone code
  };
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
  const countryCodeFieldName = name + "CountryCode";
  const phoneExtFieldName = name + "Ext";
  const phoneMask = "(999) 999-9999";

  let initialCountryCode;

  if (values.phoneCountryCode) {
    const country = getCountryByCode(values.phoneCountryCode, countries);

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
  function getSelectItems(): any {
    const codes = [];
    if (!countries) return codes;
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
        <FormSelect
          items={getSelectItems()}
          classes={{
            selectList: "form-select-list__fit-content",
            selectHeader: "",
          }}
          value={countryCodeValue}
          disabled={disabled}
          onClick={(item) => {
            setFieldValue(countryCodeFieldName, item.value);
            setCountryCodeValue(item);
          }}
          name={countryCodeFieldName}
          id={countryCodeFieldName}
          isValid={!!touched.phoneCountryCode && !errors.phoneCountryCode}
          isInvalid={!!touched.phoneCountryCode && !!errors.phoneCountryCode}
        />
        {!!touched.phoneCountryCode && !!errors.phoneCountryCode && (
          <Feedback className="position-absolute" type="invalid">
            {errors.phoneCountryCode}
          </Feedback>
        )}
      </div>

      <div className={classnames(classes.inputPhoneColumn)}>
        <RBForm.Group controlId={name}>
          <MaskedInput
            type={"text"}
            name={name}
            value={values[name]}
            onChange={handleChange}
            placeholder="(___) ___-____"
            isInvalid={!!touched[name] && !!errors[name]}
            isValid={!!touched[name] && !errors[name]}
            mask={phoneMask}
            disabled={disabled}
          />
          {!!touched[name] && !!errors[name] && (
            <Feedback className="position-absolute" type="invalid">
              {errors[name]}
            </Feedback>
          )}
        </RBForm.Group>
      </div>

      <RBForm.Group
        className={classnames(classes.inputPhoneExt)}
        controlId={phoneExtFieldName}
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
          name={phoneExtFieldName}
          value={values[phoneExtFieldName]}
          onChange={handleChange}
          disabled={disabled}
          isInvalid={
            !!touched[phoneExtFieldName] && !!errors[phoneExtFieldName]
          }
          isValid={touched[phoneExtFieldName] && !errors[phoneExtFieldName]}
          autoComplete={"off"}
          placeholder="12345"
        />
      </RBForm.Group>
    </div>
  );
};

export default FormInputPhone;
