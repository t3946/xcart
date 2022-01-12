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
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";

const LoginButtonDesktop: React.FC = function () {
  const dispatch = useDispatch();
  const user = useSelectorAccount((e) => e.user);
  const className = "hat-login-button";
  const isTabletMenuVisible = useSelectorAccount(
    (e) => e.mobileMenu.isTabletMenuVisible
  );

  function toggleMenu(isVisible: boolean) {
    HideAllMenu(dispatch);
    isVisible && dispatch(setTabletMenuIsVisible(true));
    isVisible && dispatch(setVisibleShadowPanelAction(true));
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
            "account-hat-dropdown-menu col-12 p-0 rounded-0"
          )}
          aria-labelledby={labeledBy}
        >
          <div className="sidebar-menu-wrapper">
            <LogoutButton onClick={logoutButtonClickHandler} />
          </div>
        </div>
      );
    }
  );

  const CustomToggle = React.forwardRef((props: any, ref: any) => {
    const { onClick } = props;

    const arrowClasses = [
      "login-button-desktop__arrow login-button-desktop-arrow",
      {
        "login-button-desktop-arrow__flip": isTabletMenuVisible,
      },
    ];

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
        {user.name}
        <ArrowIconMobileDesktop className={classnames(arrowClasses)} />
      </span>
    );
  });

  if (!user) {
    const path = "/login";
    const className = "hat-login-button";
    const text = "log in";

    return (
      <Link href={path}>
        <a className={className}>{text}</a>
      </Link>
    );
  }

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
