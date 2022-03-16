import React from "react";
import { useDispatch, useSelector } from "react-redux";
import classNames from "classnames";
import SidebarMenu from "@client/jsx/modules/account/components/sidebar-menu/SideBarMenu";
import { Link } from "react-router-dom";
import { hideAllMenu } from "@client/jsx/redux/actions/account-actions/MenuActions";
import { StoreDto } from "@s3stores-mail/ts/types";
import { route } from "@client/jsx/utils/AppData";

interface IProps {
  isStatic: boolean;
}

const MenuMobile: React.FC<IProps> = (props: IProps) => {
  const isStatic = props.isStatic || false;
  const dispatch = useDispatch();
  const user = useSelector((e: StoreDto) => e.user);
  const mobileMenuIsVisible = useSelector(
    (e: any) => e.mobileMenu.isMobileMenuVisible
  );

  function signInButtonTemplate() {
    if (user) {
      if (isStatic) {
        return (
          <a
            href={"/account/dashboard"}
            className="common-link text-decoration-none ms-3 mobile-menu-user-name"
            onClick={() => dispatch(hideAllMenu())}
          >
            <b>{user.name}</b>
          </a>
        );
      } else {
        return (
          <Link
            to={"/dashboard"}
            className="common-link text-decoration-none ms-3 mobile-menu-user-name"
            onClick={() => dispatch(hideAllMenu())}
          >
            <b>{user.name}</b>
          </Link>
        );
      }
    }

    if (isStatic) {
      return (
        <a
          href={"/account/login"}
          className="common-link text-decoration-none form-button form-button__outline w-auto pl-4 pr-4 mobile-menu-login-button"
          onClick={() => dispatch(hideAllMenu())}
        >
          sign in
        </a>
      );
    } else {
      return (
        <Link
          to={"/account/login"}
          className="common-link text-decoration-none form-button form-button__outline w-auto pl-4 pr-4 mobile-menu-login-button"
          onClick={() => dispatch(hideAllMenu())}
        >
          sign in
        </Link>
      );
    }
  }

  function userAvatarTemplate() {
    if (user && user.avatar_image) {
      return (
        <img
          src={user.avatar_image}
          className={"mobile-menu-avatar"}
          alt=""
        />
      );
    }

    return (
      <i
        className={
          "mobile-menu-sign-in-icon navigation-login-button__not-logged common-icon"
        }
      />
    );
  }

  const classes = {
    menu: [
      "account-hat-dropdown-menu__mobile account-hat_mobile-menu position-fixed",
      {
        "d-none": !mobileMenuIsVisible,
      },
    ],
    loginButton: [
      "mobile-menu-sign-in d-flex align-items-center",
      {
        "mobile-menu-sign-in__unauthorised": !user,
      },
    ],
  };

  return (
    <React.Fragment>
      <div
        className={classNames(classes.menu)}
        onClick={(e) => e.stopPropagation()}
      >
        <div className={classNames(classes.loginButton)}>
          {userAvatarTemplate()}

          {signInButtonTemplate()}
        </div>
        <SidebarMenu />
      </div>
    </React.Fragment>
  );
};

export default MenuMobile;
