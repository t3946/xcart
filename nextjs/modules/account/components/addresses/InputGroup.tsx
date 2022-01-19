import React from "react";
import Label from "@modules/ui/forms/Label";
import Input from "@modules/ui/forms/Input";
import MaskedInput from "@modules/ui/forms/MaskedInput";
import Feedback from "@modules/ui/forms/Feedback";
import cn from "classnames";
import Styles from "@modules/account/components/addresses/InputGroup.module.scss";
interface IProps {
  label?: string;
  placeholder?: string;
  name?: string;
  value?: any;
  type?: string;
  error?: string;
  disabled?: boolean;
  isValid?: boolean;
  isInvalid?: boolean;
  autoComplete?: string;
  mask?: string;
  component?: React.ReactNode;
  handleChange?: (e: string | React.ChangeEvent<any>) => void;
  classNames?: { container?: any };
}

const InputGroup: React.FC<IProps> = (props) => {
  const {
    label,
    placeholder,
    name,
    value,
    type = "text",
    error,
    isValid,
    isInvalid,
    disabled,
    handleChange,
    autoComplete,
    mask,
    component,
    classNames,
  } = props;

  const classes = {
    container: [
      "row",
      "justify-content-end",
      "align-items-center",
      classNames?.container,
      Styles.container,
    ],
    label: "col-md-3 col-lg-4 mb-10 mb-md-0",
    input: "col-md-9 col-lg-8",
  };

  let InputComponent: React.ReactNode;

  if (component) {
    InputComponent = component;
  } else if (mask) {
    InputComponent = (
      <MaskedInput
        type={type}
        name={name ?? ""}
        value={value}
        onChange={handleChange}
        placeholder={placeholder}
        isInvalid={isInvalid}
        isValid={isValid}
        autoComplete={autoComplete}
        disabled={disabled}
        mask={mask}
      />
    );
  } else {
    InputComponent = (
      <Input
        type={type}
        name={name ?? ""}
        value={value}
        onChange={handleChange}
        placeholder={placeholder}
        isInvalid={isInvalid}
        isValid={isValid}
        autoComplete={autoComplete}
        disabled={disabled}
      />
    );
  }
  return (
    <div className={cn(classes?.container)}>
      {label && <Label className={classes.label}>{label}</Label>}
      <div className={classes.input}>{InputComponent}</div>
      {error && (
        <Feedback className={cn(classes.input, Styles.feedback)} type="invalid">
          {error}
        </Feedback>
      )}
    </div>
  );
};

export default InputGroup;
