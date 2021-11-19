import React from "react";
import { useSelector, useDispatch } from "react-redux";
import { StoreDto } from "@s3stores-mail/ts/types";
import { route } from "@client/jsx/utils/AppData";
import { Link } from "react-router-dom";
import { Dropdown } from "react-bootstrap";
import TransitionFade from "@client/modules/account/components/shared/TransitionFade";
import HideAllMenu from "@client/modules/account/utils/hide-all-menu";
import { setTabletMenuIsVisible } from "@client/jsx/redux/actions/account-actions/MenuActions";
import { setVisibleShadowPanelAction } from "@client/jsx/redux/actions/account-actions/ShadowPanelActions";
import classnames from "classnames";
import LogoutButton from "@client/modules/account/components/sidebar-menu/LogoutButton";
import ArrowIconMobileDesktop from "@client/modules/icon/components/account/chevron-down/AccountSidebarMobileDesktop";
import UserIcon from "@client/modules/account/components/hat/LoginButton/UserIcon";

interface IProps {
  isStatic: boolean;
}

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

const LoginButtonDesktop: React.FC<IProps> = function (props: IProps) {
  const dispatch = useDispatch();
  const user = useSelector((e: StoreDto) => e.user);
  const isStatic = props.isStatic || false;
  const className = "hat-login-button";

  function toggleMenu(isVisible) {
    HideAllMenu(dispatch);
    isVisible && dispatch(setTabletMenuIsVisible(true));
    isVisible && dispatch(setVisibleShadowPanelAction(true));
  }

  if (!user) {
    const path = route("account:login");
    const text = "log in";

    if (isStatic) {
      return (
        <a href={path} className={className}>
          <UserIcon />
          <span className="hat-login-button-username">{text}</span>
        </a>
      );
    } else {
      return (
        <Link to={path} className={className}>
          <UserIcon />
          <span className="hat-login-button-username">{text}</span>
        </Link>
      );
    }
  }

  const isTabletMenuVisible = useSelector(
    (e: any) => e.mobileMenu.isTabletMenuVisible
  );

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

    const classes = {
      username: ["hat-login-button-username"],
      iconArrow: [
        "login-button-desktop__arrow",
        "login-button-desktop-arrow",
        {
          "login-button-desktop-arrow__flip": isTabletMenuVisible,
        },
      ],
    };

    return (
      <span
        className={className}
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
