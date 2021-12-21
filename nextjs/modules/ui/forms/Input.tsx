import { Form, FormControlProps } from "react-bootstrap";

import React, { ChangeEvent } from "react";
import Styles from "@modules/ui/forms/Input.module.scss";
import cn from "classnames";

interface IProps extends FormControlProps {
  type?: string;
  name: string;
  value?: any;
  disabled?: boolean;
  onChange?: (e: ChangeEvent) => void;
  className?: any;
  isValid?: boolean;
  isInvalid?: boolean;
  autoComplete?: string;
  placeholder?: string;
}
const Input = React.forwardRef<HTMLInputElement | null, IProps>(
  (props, ref) => {
    const classes = [
      Styles.input,
      props.className,
      {
        [Styles.input_valid]: props.isValid,
        [Styles.input_invalid]: props.isInvalid,
      },
    ];
    const mergeProps = {
      ...props,
      type: props.type ? props.type : "text",
      className: cn(classes),
      ref: ref,
    };
    return <Form.Control {...mergeProps} />;
  }
);

export default Input;
