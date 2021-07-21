import React from "react";
import { SidebarItemDto } from "@modules/account/ts/sidebar-item.type";
import { NavLink } from "react-router-dom";

export const SideBarMenuAccordIonItem: React.FC<SidebarItemDto> = ({
  to,
  label,
}) => {
  return (
    <NavLink
      to={to}
      className="sidebar-menu-container sidebar-menu-accordion-item"
      activeClassName="accordion-active"
      exact={true}
    >
      {label}
    </NavLink>
  );
};
