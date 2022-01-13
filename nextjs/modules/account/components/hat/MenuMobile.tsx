import React from "react";
import { useDispatch } from "react-redux";
import classNames from "classnames";
import SidebarMenu from "@modules/account/components/sidebar-menu/SideBarMenu";
import Link from "next/link";
import { hideAllMenu } from "@redux/actions/account-actions/MenuActions";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";

const MenuMobile: React.FC = () => {
  const dispatch = useDispatch();
  const user = useSelectorAccount((e: any) => e.user);
  const mobileMenuIsVisible = useSelectorAccount(
    (e: any) => e.mobileMenu.isMobileMenuVisible
  );

  function signInButtonTemplate() {
    if (user) {
      return (
        <Link href={"/dashboard"}>
          <a
            className="common-link text-decoration-none ms-3 mobile-menu-user-name"
            onClick={() => dispatch(hideAllMenu())}
          >
            <b>{user.name}</b>
          </a>
        </Link>
      );
    }

    return (
      <Link href={"/login"}>
        <a
          className="common-link text-decoration-none form-button form-button__outline w-auto pl-4 pr-4 mobile-menu-login-button"
          onClick={() => dispatch(hideAllMenu())}
        >
          sign in
        </a>
      </Link>
    );
  }

  function userAvatarTemplate() {
    if (user && user.avatar_image) {
      return (
        <img
          src={`/${user.avatar_image}`}
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
      "account-hat-dropdown-menu__mobile account-hat_mobile-menu",
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
