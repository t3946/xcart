import React from "react";
import cn from "classnames";
import Styles from "@modules/ui/forms/select/Select.module.scss";
import ReactSelect from "react-select";
import Option from "@modules/ui/forms/select/Option";
import MenuList from "@modules/ui/forms/select/MenuList";

interface IProps {
  options: any;
  classes: {
    select?: any;
  };
}

const Select = function (props: IProps) {
  const { classes, options } = props;

  return (
    <ReactSelect
      className={cn(Styles.fooSelect, classes?.select)}
      id="select"
      options={options}
      components={{ Option, MenuList }}
    />
  );
};

export default Select;
