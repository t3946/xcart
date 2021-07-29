import React from "react";
import { useDispatch, useSelector } from "react-redux";
import classNames from "classnames";
import SidebarMenu from "../sidebar-menu/SideBarMenu";
import { NavLink } from "react-router-dom";
import { hideAllMenu } from "../../../../redux/actions/account-actions/MenuActions";
import { StoreDto } from "@s3stores-mail/ts/types";

const MobileMenu: React.FC<any> = () => {
  const dispatch = useDispatch();
  const user = useSelector((e: StoreDto) => e.user);
  const isVisible = useSelector((e: any) => e.mobileMenu.isMobileMenuVisible);

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

  function signInButton() {
    if (user) {
      return (
        <NavLink
          to="/account/dashboard"
          className="common-link text-decoration-none"
          exact={true}
          onClick={() => dispatch(hideAllMenu())}
        >
          <b>{user.name}</b>
        </NavLink>
      );
    }

    return (
      <NavLink
        to="/account/login/"
        className="common-link text-decoration-none"
        exact={true}
      >
        <a
          className={
            "form-button form-button__outline w-auto pl-4 pr-4 common-link"
          }
          onClick={() => dispatch(hideAllMenu())}
        >
          sign in
        </a>
      </NavLink>
    );
  }

  return (
    <React.Fragment>
      <div
        className={classNames(classes.menu)}
        onClick={(e) => e.stopPropagation()}
      >
        <div
          className={
            "mobile-menu-sign-in d-flex align-items-center justify-content-between"
          }
        >
          <i
            className={
              "mobile-menu-sign-in-icon navigation-login-button__not-logged common-icon"
            }
          />

          {signInButton()}
        </div>
        <SidebarMenu />
      </div>

      <div className={classNames(classes.panel)} />
    </React.Fragment>
  );
};

export default MobileMenu;
