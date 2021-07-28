import classNames from "classnames";
import React from "react";
import { setMobileMenuVisible } from "../../../../redux/actions/account-actions/MobileMenuActions";
import { useDispatch, useSelector } from "react-redux";
import useCLickListener from "../../hooks/useClickListener";

const MobileTemplate: React.FC<any> = () => {
  const classes = ["navigation-login-button d-flex align-items-center"];
  const dispatch = useDispatch();

  if (appData.user) {
    classes.push("navigation-login-button__logged");
  } else {
    classes.push("navigation-login-button__not-logged");
  }

  useCLickListener(() => {
    dispatch(setMobileMenuVisible(false));
  });

  const isVisible = useSelector((e: any) => e.mobileMenu.isVisible);

  function openMenu(e) {
    e.stopPropagation();
    dispatch(setMobileMenuVisible(!isVisible));
  }

  return (
    <div onClick={openMenu} className="d-md-none hat-navigation-item d-flex align-items-center">
      <i className={classNames(classes)} />
    </div>
  );
};

export default MobileTemplate;
