import React from "react";
import Styles from "@modules/ui/forms/Textarea.module.scss";
import StylesInput from "@modules/ui/forms/Input.module.scss";
import cn from "classnames";

interface IProps {
  name: string;
  value?: any;
  disabled?: boolean;
  onChange?: (e: React.ChangeEvent) => void;
  className?: any;
  isValid?: boolean;
  isInvalid?: boolean;
  placeholder?: string;
}

const Textarea: React.FC<IProps> = React.forwardRef<
  HTMLInputElement | null,
  IProps
>((props) => {
  const {
    name,
    value,
    onChange,
    className,
    disabled,
    isValid,
    isInvalid,
    placeholder,
  } = props;

  const classes = {
    textarea: [
      Styles.textarea,
      className,
      {
        [StylesInput.input_valid]: isValid,
        [StylesInput.input_invalid]: isInvalid,
      },
    ],
  };

  return (
    <textarea
      name={name}
      value={value}
      disabled={disabled}
      onChange={onChange}
      className={cn(classes.textarea)}
      placeholder={placeholder}
    />
  );
});

export default Textarea;
