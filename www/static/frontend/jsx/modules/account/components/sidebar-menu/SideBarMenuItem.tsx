import React from "react";
import { NavLink } from "react-router-dom";

interface sideBarMenuItemPropsDto {
  to: string;
  label: string;
}

export const SideBarMenuItem: React.FC<sideBarMenuItemPropsDto> = ({
  to,
  label,
}) => {
  return (
    <NavLink
      to={to}
      exact={true}
      activeClassName="active-route"
      className="sidebar-menu-container"
    >
      <span>{label}</span>
    </NavLink>
  );
};
