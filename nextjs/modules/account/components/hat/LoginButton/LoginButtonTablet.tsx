import classNames from "classnames";
import React from "react";
import SidebarMenu from "@modules/account/components/sidebar-menu/SideBarMenu";
import { Dropdown } from "react-bootstrap";
import { useDispatch, useSelector } from "react-redux";
import { setTabletMenuIsVisible } from "@redux/actions/account-actions/MenuActions";
import HideAllMenu from "@modules/account/utils/hide-all-menu";
import { setVisibleShadowPanelAction } from "@redux/actions/account-actions/ShadowPanelActions";
import TransitionFade from "@modules/account/components/shared/TransitionFade";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import UserIcon from "@modules/account/components/hat/LoginButton/UserIcon";

import Styles from "@modules/account/components/hat/LoginButton/LoginButtonTablet.module.scss";

const LoginButtonTablet: React.FC<any> = () => {
  const dispatch = useDispatch();
  const user = useSelectorAccount((e) => e.user);
  const text = user ? user.name : "log in";

  const CustomToggle = React.forwardRef((props, ref) => {
    const { onClick } = props;

    return (
      <div
        onClick={(e) => {
          onClick(e);
        }}
        ref={ref}
        className={classNames(
          "navigation-login-button d-none d-md-flex navigation-login-button__tablet align-items-center justify-content-between",
          {
            "navigation-login-button__active": props["aria-expanded"],
          }
        )}
      >
        <UserIcon />

        <span className={classNames(Styles.userName, "ms-1")}>{text}</span>

        <i
          className={classNames(
            "navigation-login-button-arrow arrow-rotatable",
            {
              "arrow-rotatable__rotated": props["aria-expanded"],
            }
          )}
        />
      </div>
    );
  });

  const CustomMenu = React.forwardRef(
    ({ children, style, className, "aria-labelledby": labeledBy }, ref) => {
      return (
        <div
          ref={ref}
          className={classNames(
            className,
            "account-hat-dropdown-menu col-12 p-0 rounded-0"
          )}
          aria-labelledby={labeledBy}
        >
          <SidebarMenu />
        </div>
      );
    }
  );

  const isTabletMenuVisible = useSelector(
    (e: any) => e.mobileMenu.isTabletMenuVisible
  );

  function toggleMenu(isVisible) {
    HideAllMenu(dispatch);
    isVisible && dispatch(setTabletMenuIsVisible(true));
    isVisible && dispatch(setVisibleShadowPanelAction(true));
  }

  if (!user) {
    const path = "/account/login";

    return (
      <a
        href={path}
        className={classNames(
          "navigation-login-button",
          "d-none",
          "d-md-flex",
          "navigation-login-button__tablet",
          "align-items-center",
          "text-decoration-none",
          "justify-content-evenly",
          Styles.button
        )}
      >
        <UserIcon />
        <span className="hat-login-button-username">{text}</span>
      </a>
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

export default LoginButtonTablet;
