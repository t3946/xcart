import React from "react";
import Styles from "@modules/ui/forms/select/MenuList.module.scss";
import { components } from "react-select";

const MenuList = function (props: any) {
  const RSMenuList = components.MenuList;

  return (
    <RSMenuList {...props} className={Styles.menu}>
      {props.children}
    </RSMenuList>
  );
};

export default MenuList;
