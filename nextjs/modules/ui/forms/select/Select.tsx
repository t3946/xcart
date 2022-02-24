import React from "react";
import cn from "classnames";
import ReactSelect, { components } from "react-select";

import Input from "@modules/ui/forms/select/Input";
import Control from "@modules/ui/forms/select/Control";
import Option from "@modules/ui/forms/select/Option";
import Menu from "@modules/ui/forms/select/Menu";
import MenuList from "@modules/ui/forms/select/MenuList";
import IndicatorsContainer from "@modules/ui/forms/select/IndicatorsContainer";
import DropdownIndicator from "@modules/ui/forms/select/DropdownIndicator";
import IndicatorSeparator from "@modules/ui/forms/select/IndicatorSeparator";

import Styles from "@modules/ui/forms/select/Select.module.scss";

interface IProps {
  options: any;
  disabled?: boolean;
  name: string;
  value: { value: string | number; label: string };
  clearable?: boolean;
  isValid?: boolean;
  isInvalid?: boolean;
  defaultIsOpen?: boolean;
  isSearchable?: boolean;
  onChange?: (value: any) => void;
  placeholder?: React.ReactNode | string;
  classes?: {
    select?: any;
    control?: any;
    menu?: any;
    list?: any;
    indicator?: any;
    indicatorsContainer?: any;
    indicatorSeparator?: any;
    option?: any;
    valueContainer?: any;
  };
}

const Select = function (props: IProps) {
  const {
    classes,
    options,
    placeholder = "",
    disabled,
    value,
    onChange,
    name,
    isValid,
    isInvalid,
    clearable = true,
    defaultIsOpen = false,
    isSearchable = true,
  } = props;
  return (
    <ReactSelect
      className={cn(Styles.select, classes?.select)}
      defaultMenuIsOpen={defaultIsOpen}
      isClearable={clearable}
      onChange={(newValue) => {
        value !== newValue &&
          onChange &&
          onChange({ target: { name, value: newValue } });
      }}
      value={value}
      name={name}
      options={options}
      classes={classes}
      isSearchable={isSearchable}
      isValid={isValid}
      isInvalid={isInvalid}
      isDisabled={disabled}
      placeholder={placeholder}
      components={{
        Option,
        Menu,
        MenuList,
        Control,
        Input,
        IndicatorsContainer,
        DropdownIndicator,
        IndicatorSeparator,
        ValueContainer: Component(
          components.ValueContainer,
          classes?.valueContainer
        ),
      }}
    />
  );
};

const Component = (RSComponent: any, className?: any) => {
  return (props: any) => {
    return <RSComponent {...props} className={cn(className)} />;
  };
};

export default Select;
