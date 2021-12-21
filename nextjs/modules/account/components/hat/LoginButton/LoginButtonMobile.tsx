import classNames from "classnames";
import React from "react";
import { useDispatch, useSelector } from "react-redux";
import { setMobileMenuIsVisible } from "@redux/actions/account-actions/MenuActions";
import HideAllMenu from "@modules/account/utils/hide-all-menu";
import { setVisibleShadowPanelAction } from "@redux/actions/account-actions/ShadowPanelActions";

const MobileTemplate: React.FC<any> = () => {
  const user = useSelector((e) => e.user);
  const classes = ["navigation-login-button d-flex align-items-center"];
  const dispatch = useDispatch();

  if (user) {
    classes.push("navigation-login-button__logged");
  } else {
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
      <i className={classNames(classes)} />
    </div>
  );
};

export default MobileTemplate;
