import React from "react";
import Styles from "@modules/ui/forms/select/Option.module.scss";
import { components } from "react-select";

const Option = function (props: any) {
  const RSOption = components.Option;

  return (
    <RSOption {...props} className={Styles.option}>
      {props.children}
    </RSOption>
  );
};

export default Option;
