import React from "react";
import { SidebarItemDto } from "@modules/account/ts/types/sidebar-item.type";
import { NavLink } from "react-router-dom";
import { setMobileMenuVisible } from "../../../../redux/actions/account-actions/MobileMenuActions";
import { useDispatch } from "react-redux";

export const SideBarMenuAccordIonItem: React.FC<SidebarItemDto> = ({
  to,
  label,
}) => {
  const dispatch = useDispatch();
  return (
    <NavLink
      to={to}
      className="sidebar-menu-container sidebar-menu-accordion-item"
      activeClassName="accordion-active"
      exact={true}
      onClick={() => dispatch(setMobileMenuVisible(false))}
    >
      {label}
    </NavLink>
  );
};
