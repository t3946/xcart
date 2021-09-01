import classNames from "classnames";
import React from "react";
import {
  hideAllMenu,
  setMobileMenuIsVisible,
} from "../../../../redux/actions/account-actions/MenuActions";
import { useDispatch, useSelector } from "react-redux";
import useCLickListener from "../../hooks/useClickListener";
import { StoreDto } from "@s3stores-mail/ts/types";

const MobileTemplate: React.FC<any> = () => {
  const user = useSelector((e: StoreDto) => e.user);
  const classes = ["navigation-login-button d-flex align-items-center"];
  const dispatch = useDispatch();

  if (user) {
    classes.push("navigation-login-button__logged");
  } else {
    classes.push("navigation-login-button__not-logged");
  }

  useCLickListener(() => {
    dispatch(hideAllMenu());
  });

  const isVisible = useSelector((e: any) => e.mobileMenu.isVisible);

  function openMenu(e) {
    e.stopPropagation();
    dispatch(setMobileMenuIsVisible(!isVisible));
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
