import React from "react";
import { NavLink } from "react-router-dom";
import classNames from "classnames";

interface sideBarMenuItemPropsDto {
  to: string;
  label: string;
  className?: any;
}

export const SideBarMenuItem: React.FC<sideBarMenuItemPropsDto> = ({
  to,
  label,
  className,
}) => {
  return (
    <NavLink
      to={to}
      exact={true}
      activeClassName="active-route"
      className={classNames("sidebar-menu-container", className)}
    >
      <span>{label}</span>
    </NavLink>
  );
};
