import React from "react";
import cn from "classnames";
import ReactSelect from "react-select";
import Control from "@modules/ui/forms/select/Control";
import Option from "@modules/ui/forms/select/Option";
import Menu from "@modules/ui/forms/select/Menu";
import MenuList from "@modules/ui/forms/select/MenuList";
import IndicatorSeparator from "@modules/ui/forms/select/Separator";
import DropdownIndicator from "@modules/ui/forms/select/DropdownIndicator";

import Styles from "@modules/ui/forms/select/Select.module.scss";

interface IProps {
  options: any;
  disabled: boolean;
  value: { value: string | number; label: string };
  clearable?: boolean;
  isValid?: boolean;
  isInvalid?: boolean;
  onChange?: () => void;
  placeholder?: React.ReactNode | string;
  classes?: {
    select?: any;
    control?: any;
    menu?: any;
    list?: any;
  };
}

const Select = function (props: IProps) {
  const {
    classes,
    options,
    placeholder,
    disabled,
    value,
    onChange,
    isValid,
    isInvalid,
    clearable = true,
  } = props;

  return (
    <ReactSelect
      className={cn(Styles.select, classes?.select)}
      isClearable={clearable}
      onChange={onChange}
      value={value}
      options={options}
      classes={classes}
      isValid={isValid}
      isInvalid={isInvalid}
      isDisabled={disabled}
      placeholder={placeholder ?? ""}
      components={{
        Option,
        Menu,
        MenuList,
        Control,
        // IndicatorSeparator,
        // DropdownIndicator,
      }}
    />
  );
};

export default Select;
