import React from "react";
import { NavLink } from "react-router-dom";
import classNames from "classnames";
import { setMobileMenuVisible } from "../../../../redux/actions/account-actions/MobileMenuActions";
import { useDispatch } from "react-redux";

interface sideBarMenuItemPropsDto {
  to: string;
  label: string;
  className?: any;
  onClick?: any;
}

export const SideBarMenuItem: React.FC<sideBarMenuItemPropsDto> = ({
  to,
  label,
  className,
}) => {
  const dispatch = useDispatch();

  return (
    <NavLink
      to={to}
      exact={true}
      activeClassName="active-route"
      className={classNames("sidebar-menu-container", className)}
      onClick={() => dispatch(setMobileMenuVisible(false))}
    >
      <span>{label}</span>
    </NavLink>
  );
};
