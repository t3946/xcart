import React from "react";
import { components } from "react-select";
import ChevronDown from "@modules/icon/components/font-awesome/chevron-down/Regular";

import Styles from "@modules/ui/forms/select/DropdownIndicator.module.scss";

const DropdownIndicator = (props: any) => {
  return (
    <components.DropdownIndicator {...props}>
      <ChevronDown className={Styles.chevron} />
      {props.children}
    </components.DropdownIndicator>
  );
};

export default DropdownIndicator;
