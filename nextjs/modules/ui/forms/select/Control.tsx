import React from "react";
import cn from "classnames";
import { components } from "react-select";

import Styles from "@modules/ui/forms/select/Control.module.scss";

const Control = function (props: any) {
  const RSControl = components.Control;

  return (
    <RSControl
      {...props}
      className={cn(Styles.control, props.selectProps.classes?.control, {
        [Styles.control_valid]: props.selectProps.isValid,
        [Styles.control_focus]: props.isFocused,
        [Styles.control_valid_focus]:
          props.isFocused && props.selectProps.isValid,
        [Styles.control_invalid]: props.selectProps.isInvalid,
        [Styles.control_invalid_focus]:
          props.isFocused && props.selectProps.isInvalid,
      })}
    >
      {props.children}
    </RSControl>
  );
};

export default Control;
