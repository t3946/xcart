import classNames from "classnames";
import React from "react";
import { useDispatch, useSelector } from "react-redux";
import { setMobileMenuIsVisible } from "@redux/actions/account-actions/MenuActions";
import HideAllMenu from "@modules/account/utils/hide-all-menu";
import { setVisibleShadowPanelAction } from "@redux/actions/account-actions/ShadowPanelActions";
import UserIcon from "@modules/account/components/hat/LoginButton/UserIcon";

const MobileTemplate: React.FC<any> = () => {
  const user = useSelector((e) => e.user);
  const className = "navigation-login-button d-flex align-items-center";
  const dispatch = useDispatch();

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
        <UserIcon className={className} />
      ) : (
        <i
          className={classNames(
            className,
            "navigation-login-button__not-logged"
          )}
        />
      )}
    </div>
  );
};

export default MobileTemplate;
