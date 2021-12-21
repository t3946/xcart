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
  name: string;
  value: any;
  type?: string;
  error?: string;
  disabled?: boolean;
  isValid?: boolean;
  isInvalid?: boolean;
  autoComplete?: string;
  mask?: string;
  handleChange: (e: string | React.ChangeEvent<any>) => void;
  classes?: {
    group?: any;
    label?: any;
    input?: any;
    feedback?: any;
  };
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
    classes,
    autoComplete,
    mask,
  } = props;
  let InputComponent;
  if (mask) {
    InputComponent = (
      <MaskedInput
        type={type}
        name={name}
        value={value}
        onChange={handleChange}
        placeholder={placeholder}
        isInvalid={isInvalid}
        isValid={isValid}
        autoComplete={autoComplete}
        disabled={disabled}
        className={Styles.input}
        mask={mask}
      />
    );
  } else {
    InputComponent = (
      <Input
        type={type}
        name={name}
        value={value}
        onChange={handleChange}
        placeholder={placeholder}
        isInvalid={isInvalid}
        isValid={isValid}
        autoComplete={autoComplete}
        disabled={disabled}
        className={Styles.input}
      />
    );
  }
  return (
    <div className="d-flex justify-content-end mb-20 flex-wrap">
      {label && (
        <Label error={isInvalid} className={"flex-grow-1 mb-0"}>
          {label}
        </Label>
      )}
      {InputComponent}
      <Feedback
        className={cn(Styles.group__feedback, Styles.feedback)}
        type="invalid"
      >
        {error}
      </Feedback>
    </div>
  );
};

export default InputGroup;
