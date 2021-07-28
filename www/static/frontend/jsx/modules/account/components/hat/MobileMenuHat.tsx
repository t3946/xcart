import React from "react";
import { useSelector } from "react-redux";
import classNames from "classnames";
import SidebarMenu from "../sidebar-menu/SideBarMenu";

const MobileMenu: React.FC<any> = () => {
  const isVisible = useSelector((e: any) => e.mobileMenu.isVisible);

  const classes = {
    menu: [
      "account-hat-dropdown-menu__mobile account-hat_mobile-menu",
      {
        "d-none": !isVisible,
      },
    ],
    panel: [
      "background-panel",
      {
        "d-none": !isVisible,
      },
    ],
  };

  return (
    <React.Fragment>
      <div
        className={classNames(classes.menu)}
        onClick={(e) => e.stopPropagation()}
      >
        <SidebarMenu />
      </div>

      <div className={classNames(classes.panel)} />
    </React.Fragment>
  );
};

export default MobileMenu;
