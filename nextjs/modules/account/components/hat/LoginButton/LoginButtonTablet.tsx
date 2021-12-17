import classNames from "classnames";
import React from "react";
import SidebarMenu from "@modules/account/components/sidebar-menu/SideBarMenu";
import { Dropdown } from "react-bootstrap";
import { useDispatch, useSelector } from "react-redux";
import { setTabletMenuIsVisible } from "@redux/actions/account-actions/MenuActions";
import { StoreDto } from "@s3stores-mail/ts/types";
import HideAllMenu from "@modules/account/utils/hide-all-menu";
import { setVisibleShadowPanelAction } from "@redux/actions/account-actions/ShadowPanelActions";
import TransitionFade from "@modules/account/components/shared/TransitionFade";
import UserIcon from "@modules/account/components/hat/LoginButton/UserIcon";

const LoginButtonTablet: React.FC<any> = () => {
  const dispatch = useDispatch();
  const user = useSelector((e: StoreDto) => e.user);
  const text = user ? user.name : "log in";

  const CustomToggle = React.forwardRef((props: any, ref: any) => {
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

        {text}

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

  const CustomMenu = React.forwardRef((props: any, ref: any) => {
    const { className, "aria-labelledby": labeledBy } = props;

    return (
      <div
        ref={ref}
        className={classNames(
          className,
          "account-hat-dropdown-menu col-12 p-0 rounded-0 border-0"
        )}
        aria-labelledby={labeledBy}
      >
        <SidebarMenu />
      </div>
    );
  });

  const isTabletMenuVisible = useSelector(
    (e: any) => e.mobileMenu.isTabletMenuVisible
  );

  function toggleMenu(isVisible) {
    HideAllMenu(dispatch);
    isVisible && dispatch(setTabletMenuIsVisible(true));
    isVisible && dispatch(setVisibleShadowPanelAction(true));
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
