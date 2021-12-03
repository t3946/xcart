import React from "react";
import { useSelector, useDispatch } from "react-redux";
import { route } from "@utils/AppData";
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
import RotateStyles from "styles/Rotate.module.scss";
import Styles from "@modules/account/components/hat/LoginButton/LoginButton.module.scss";
import cn from "classnames";

const AccountLink: React.FC = function () {
  const isStatic = !document.location.pathname.startsWith("/account");
  const classes = [
    "sidebar-menu-item",
    "sidebar-menu_top-level-item",
    "text-decoration-none",
  ];

  if (isStatic) {
    return (
      <a className={classnames(classes)} href={route("account:index")}>
        Account
      </a>
    );
  }

  return;
};

const LoginButtonDesktop: React.FC = function () {
  const dispatch = useDispatch();
  const user = useSelector((e: any) => e.user);
  const isTabletMenuVisible = useSelector(
    (e: any) => e.mobileMenu.isTabletMenuVisible
  );
  const classes = {
    button: [
      Styles.hatLoginButton,
      "d-flex",
      "align-items-center",
      "justify-content-center",
      "position-relative",
      "cursor-pointer",
    ],

    username: ["hat-login-button-username"],

    iconArrow: [
      isTabletMenuVisible ? RotateStyles.rotate__180 : RotateStyles.rotate__0,
      "login-button-desktop__arrow",
      "login-button-desktop-arrow",
      {
        "login-button-desktop-arrow__flip": isTabletMenuVisible,
      },
    ],
  };

  function toggleMenu(isVisible) {
    HideAllMenu(dispatch);
    isVisible && dispatch(setTabletMenuIsVisible(true));
    isVisible && dispatch(setVisibleShadowPanelAction(true));
  }

  if (!user) {
    const path = route("account:login");
    const text = "log in";

    return (
      <Link href={path}>
        <a className={cn(classes.button)}>
          <UserIcon />
          <span className="hat-login-button-username">{text}</span>
        </a>
      </Link>
    );
  }

  function logoutButtonClickHandler() {
    HideAllMenu(dispatch);
    dispatch(setVisibleShadowPanelAction(false));
  }

  const CustomMenu = React.forwardRef((menuProps: any, ref: any) => {
    const { className, "aria-labelledby": labeledBy } = menuProps;

    return (
      <div
        ref={ref}
        className={classnames(
          className,
          "account-hat-dropdown-menu col-12 p-0 rounded-0"
        )}
        aria-labelledby={labeledBy}
      >
        <div className="sidebar-menu-wrapper">
          <AccountLink />
          <LogoutButton onClick={logoutButtonClickHandler} />
        </div>
      </div>
    );
  });

  const CustomToggle = React.forwardRef((props: any, ref: any) => {
    const { onClick } = props;

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

        <ArrowIconMobileDesktop className={classnames(classes.iconArrow)} />
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
