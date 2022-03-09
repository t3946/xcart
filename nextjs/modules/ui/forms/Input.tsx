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
  onBlur?: (e: any) => void;
  className?: any;
  isValid?: boolean;
  isInvalid?: boolean;
  autoComplete?: string;
  placeholder?: string;
  maxLength?: number;
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

    if (props.as === "textarea") {
      classes.push(Styles.input_textarea);
    }

    const { maxLength = Number.MAX_VALUE, onChange, type = "text" } = props;
    const mergeProps = {
      ...props,
      type: type === "number" ? "text" : type,
      onChange: (e) => {
        if (type === "number") {
          e.target.value.length <= maxLength &&
            onChange &&
            onChange({
              target: {
                name: mergeProps.name,
                value: Math.abs(parseInt(e.target.value)) || 0,
              },
            });
          return;
        }
        e.target.value.length <= maxLength && onChange && onChange(e);
      },
      className: cn(classes),
      ref: ref,
    };

    return <Form.Control {...mergeProps} />;
  }
);

export default Input;
