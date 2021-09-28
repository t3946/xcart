import React from "react";
import { useSelector, useDispatch } from "react-redux";
import { StoreDto } from "@s3stores-mail/ts/types";
import { route } from "@client/jsx/utils/AppData";
import { Link, useHistory } from "react-router-dom";
import { Dropdown } from "react-bootstrap";
import TransitionFade from "@client/modules/account/components/shared/TransitionFade";
import HideAllMenu from "@client/modules/account/utils/hide-all-menu";
import { setTabletMenuIsVisible } from "@client/jsx/redux/actions/account-actions/MenuActions";
import { setVisibleShadowPanelAction } from "@client/jsx/redux/actions/account-actions/ShadowPanelActions";
import classNames from "classnames";
import LogoutButton from "@client/modules/account/components/sidebar-menu/LogoutButton";

interface PropsInterface {
  isStatic: boolean;
}

const LoginButtonDesktop: React.FC<PropsInterface> = function (
  props: PropsInterface
) {
  const maxUsernameLength = 10;
  const dispatch = useDispatch();
  const user = useSelector((e: StoreDto) => e.user);
  const isStatic = props.isStatic || false;

  function toggleMenu(isVisible) {
    HideAllMenu(dispatch);
    isVisible && dispatch(setTabletMenuIsVisible(true));
    isVisible && dispatch(setVisibleShadowPanelAction(true));
  }

  if (!user) {
    const path = route("account:login");
    const className = "hat-login-button";
    const text = "log in";

    if (isStatic) {
      return (
        <a href={path} className={className}>
          {text}
        </a>
      );
    } else {
      return (
        <Link to={path} className={className}>
          {text}
        </Link>
      );
    }
  }

  function truncateUsername(username) {
    if (username.length <= maxUsernameLength) {
      return username;
    } else {
      return username.substr(0, maxUsernameLength - 1) + "…";
    }
  }

  const username = truncateUsername(user.name);
  const title = username === user.name ? "" : user.name;
  const className = "hat-login-button";

  const isTabletMenuVisible = useSelector(
    (e: any) => e.mobileMenu.isTabletMenuVisible
  );

  function logoutButtonClickHandler() {
    HideAllMenu(dispatch);
    dispatch(setVisibleShadowPanelAction(false));
  }

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
          <div className="sidebar-menu-wrapper">
            <LogoutButton onClick={logoutButtonClickHandler} />
          </div>
        </div>
      );
    }
  );

  const CustomToggle = React.forwardRef((props, ref) => {
    const { onClick } = props;
    return (
      <span
        className={className}
        title={title}
        ref={ref}
        onClick={(e) => {
          onClick(e);
        }}
      >
        {username}
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
