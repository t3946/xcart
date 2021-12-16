import { Form } from "react-bootstrap";

import React, { ChangeEvent } from "react";
import Styles from "@modules/ui/forms/Input.module.scss";
import cn from "classnames";

interface IProps {
  name: string;
  value?: any;
  disabled?: boolean;
  onChange?: (e: ChangeEvent) => void;
  className?: any;
  isValid?: boolean;
  isInvalid?: boolean;
  autoComplete?: string;
}

const Input: React.FC<IProps> = (props: IProps) => {
  const { name, value, onChange, disabled, isValid, isInvalid, autoComplete } = props;

  const classes = [
    Styles.input,
    props.className,
    {
      [Styles.input_valid]: isValid,
      [Styles.input_invalid]: isInvalid,
    },
  ];

  return (
    <Form.Control
      type="text"
      name={name}
      value={value}
      onChange={onChange}
      className={cn(classes)}
      isInvalid={isInvalid}
      isValid={isValid}
      autoComplete={autoComplete}
      disabled={disabled}
    />
  );
};

export default Input;
