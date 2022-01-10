import React from "react";
import { useDispatch } from "react-redux";
import Link from "next/link";
import { Dropdown } from "react-bootstrap";
import TransitionFade from "@modules/account/components/shared/TransitionFade";
import HideAllMenu from "@modules/account/utils/hide-all-menu";
import { setTabletMenuIsVisible } from "@redux/actions/account-actions/MenuActions";
import { setVisibleShadowPanelAction } from "@redux/actions/account-actions/ShadowPanelActions";
import classnames from "classnames";
import LogoutButton from "@modules/account/components/sidebar-menu/LogoutButton";
import ArrowIconMobileDesktop from "@modules/icon/components/account/chevron-down/AccountSidebarMobileDesktop";
import UserIcon from "@modules/account/components/hat/LoginButton/UserIcon";
import RotateStyles from "styles/modules/Rotate.module.scss";
import StylesCommon from "@modules/account/components/hat/LoginButton/LoginButton.module.scss";
import cn from "classnames";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";

import Styles from "@modules/account/components/hat/LoginButton/LoginButtonDesktop.module.scss";

const AccountLink: React.FC = function () {
  const classes = [
    "sidebar-menu-item",
    "sidebar-menu_top-level-item",
    "text-decoration-none",
  ];

  return (
    <Link href={"/"}>
      <a className={classnames(classes)}>Account</a>
    </Link>
  );
};

const LoginButtonDesktop: React.FC = function () {
  const dispatch = useDispatch();
  const user = useSelectorAccount((e) => e.user);
  const className = "hat-login-button";
  const isTabletMenuVisible = useSelectorAccount(
    (e) => e.mobileMenu.isTabletMenuVisible
  );

  const classes = {
    button: [
      StylesCommon.hatLoginButton,
      Styles.button,
      "d-flex",
      "align-items-center",
      "position-relative",
      "cursor-pointer",
      "text-decoration-none",
      { [Styles.button_logined]: user },
    ],

    username: ["hat-login-button-username", Styles.username, "text-center"],

    iconArrow: [
      isTabletMenuVisible ? RotateStyles.rotate__180 : RotateStyles.rotate__0,
      "login-button-desktop__arrow",
      "login-button-desktop-arrow",
      {
        "login-button-desktop-arrow__flip": isTabletMenuVisible,
      },
    ],

    dropdownItem: ["justify-content-center"],
  };
  const routes = useSelectorAccount((e) => e.routes);

  function toggleMenu(isVisible: boolean) {
    HideAllMenu(dispatch);
    isVisible && dispatch(setTabletMenuIsVisible(true));
    isVisible && dispatch(setVisibleShadowPanelAction(true));
  }

  if (!user) {
    return (
      <Link href={"/login"}>
        <a className={cn(classes.button)}>
          <UserIcon />
          <span className="hat-login-button-username">log in</span>
        </a>
      </Link>
    );
  }

  function logoutButtonClickHandler() {
    HideAllMenu(dispatch);
    dispatch(setVisibleShadowPanelAction(false));
  }

  const CustomMenu = React.forwardRef(
    ({ className, "aria-labelledby": labeledBy }: any, ref: any) => {
      return (
        <div
          ref={ref}
          className={classnames(
            className,
            "account-hat-dropdown-menu col-12 p-0 rounded-0 border-0"
          )}
          aria-labelledby={labeledBy}
        >
          <div className="sidebar-menu-wrapper">
            <LogoutButton
              onClick={logoutButtonClickHandler}
              classes={classes.dropdownItem}
            />
          </div>
        </div>
      );
    }
  );

  const CustomToggle = React.forwardRef((props: any, ref: any) => {
    const { onClick } = props;
    const arrowClasses = [
      isTabletMenuVisible ? RotateStyles.rotate__180 : RotateStyles.rotate__0,
      Styles.arrowIcon,
      "flex-shrink-0",
      {
        "login-button-desktop-arrow__flip": isTabletMenuVisible,
      },
    ];

    return (
      <span
        className={cn(classes.button)}
        title={user.name}
        ref={ref}
        onClick={(e) => {
          onClick(e);
        }}
      >
        <UserIcon />
        <span className={classnames(classes.username)}>{user.name}</span>

        <ArrowIconMobileDesktop className={classnames(arrowClasses)} />
      </span>
    );
  });

  return (
    <Dropdown
      show={isTabletMenuVisible}
      onToggle={(prop) => {
        toggleMenu(prop);
      }}
      onClick={(e) => e.stopPropagation()}
    >
      <Dropdown.Toggle id="dropdown-basic" as={CustomToggle} />
      <TransitionFade show={isTabletMenuVisible}>
        <Dropdown.Menu as={CustomMenu} />
      </TransitionFade>
    </Dropdown>
  );
};

export default LoginButtonDesktop;
