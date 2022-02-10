import classNames from "classnames";
import React from "react";
import { useDispatch, useSelector } from "react-redux";
import { StoreDto } from "@s3stores-mail/ts/types";
import { setMobileMenuIsVisible } from "@client/jsx/redux/actions/account-actions/MenuActions";
import HideAllMenu from "@client/jsx/modules/account/utils/hide-all-menu";
import { setVisibleShadowPanelAction } from "@client/jsx/redux/actions/account-actions/ShadowPanelActions";
import UserIcon from "@client/modules/account/components/hat/LoginButton/UserIcon";

const MobileTemplate: React.FC<any> = () => {
  const user = useSelector((e: StoreDto) => e.user);
  const classes = ["navigation-login-button d-flex align-items-center"];
  const dispatch = useDispatch();

  if (!user) {
    classes.push("navigation-login-button__not-logged");
  }

  function openMenu(e) {
    e.stopPropagation();
    HideAllMenu(dispatch);
    dispatch(setMobileMenuIsVisible(true));
    dispatch(setVisibleShadowPanelAction(true));
  }

  return (
    <div
      onClick={openMenu}
      className="d-md-none hat-navigation-item d-flex align-items-center"
    >
      {user ? (
        <UserIcon className={classes} />
      ) : (
        <i className={classNames(classes)} />
      )}
    </div>
  );
};

export default MobileTemplate;
