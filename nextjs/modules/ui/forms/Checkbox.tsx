import React from "react";
import { Form as RBForm } from "react-bootstrap";
import CheckboxArrow from "@modules/icon/components/account/checkbox-arrow/CheckboxArrow";
import cn from "classnames";

import Styles from "@modules/ui/forms/Checkbox.module.scss";

interface IProps {
  name: string;
  label: string | React.ReactNode;
  checked: any;
  disabled?: any;
  isValid?: boolean;
  isInvalid?: boolean;
  onChange: (e: React.ChangeEvent<HTMLInputElement>) => void;
  classes?: {
    container?: any;
  };
}

const Checkbox: React.FC<IProps> = ({
  name,
  label,
  checked,
  disabled,
  onChange,
  classes,
  isValid,
  isInvalid,
}) => {
  return (
    <RBForm.Label
      className={cn(
        Styles.checkbox,
        classes?.container,
        "mb-0 align-items-start align-items-md-center d-flex position-relative",
        {
          [Styles.checkbox_disabled]: disabled,
          [Styles.checkbox_valid]: isValid,
          [Styles.checkbox_invalid]: isInvalid,
        }
      )}
    >
      <input
        name={name}
        className="d-none"
        type="checkbox"
        checked={checked}
        onChange={onChange}
        disabled={disabled}
      />
      <CheckboxArrow
        className={[
          Styles.checkboxMarker,
          { [Styles.checkboxMarker_active]: checked },
        ]}
      />
      <span className={Styles.label}>{label}</span>
    </RBForm.Label>
  );
};

export default Checkbox;
