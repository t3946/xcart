import React from "react";
import InputMask from "react-input-mask";
import Input from "@modules/ui/forms/Input";

interface IProps extends FormControlProps {
  type: string;
  name: string;
  value?: any;
  disabled?: boolean;
  onChange?: (e: ChangeEvent) => void;
  className?: any;
  isValid?: boolean;
  isInvalid?: boolean;
  autoComplete?: string;
  placeholder?: string;
  mask: string;
}

const MaskedInput: React.FC<IProps> = React.forwardRef<
  HTMLInputElement | null,
  IProps
>((props, ref) => {
  const {
    mask,
    name,
    value,
    onChange,
    className,
    disabled,
    isValid,
    isInvalid,
    autoComplete,
    placeholder,
  } = props;
  return (
    <InputMask
      mask={mask}
      value={value}
      disabled={disabled}
      onChange={onChange}
    >
      {() => (
        <Input
          type="text"
          name={name}
          isValid={isValid}
          className={className}
          isInvalid={isInvalid}
          autoComplete={autoComplete}
          placeholder={placeholder}
          disabled={disabled}
        />
      )}
    </InputMask>
  );
});

export default MaskedInput;
