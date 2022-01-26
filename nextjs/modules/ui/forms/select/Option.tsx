import React from "react";
import cn from "classnames";
import { components } from "react-select";

import Styles from "@modules/ui/forms/select/Option.module.scss";

const Option = function (props: any) {
  const RSOption = components.Option;
  const { isSelected, isFocused } = props;

  return (
    <RSOption
      {...props}
      className={cn(Styles.option, {
        [Styles.option_selected]: isSelected,
        [Styles.option_focus]: isFocused,
      })}
    >
      {props.children}
    </RSOption>
  );
};

export default Option;
