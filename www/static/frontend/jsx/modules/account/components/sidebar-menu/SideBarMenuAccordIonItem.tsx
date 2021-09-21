import React from "react";
import { SidebarItem } from "@client/jsx/modules/account/ts/types/sidebar-item.type";
import { NavLink } from "react-router-dom";
import { hideAllMenu } from "@client/jsx/redux/actions/account-actions/MenuActions";
import { useDispatch } from "react-redux";

export const SideBarMenuAccordIonItem: React.FC<SidebarItem> = ({
  to,
  label,
  badge,
}) => {
  const dispatch = useDispatch();

  function badgeTemplate(): any {
    if (!badge) {
      return;
    }

    return (
      <span className="sidebar-badge sidebar-menu-item_badge d-flex align-items-center justify-content-center rounded-pill fw-bold">
        {badge}
      </span>
    );
  }

  return (
    <NavLink
      to={to}
      className="sidebar-menu-item sidebar-menu-item__accordion text-decoration-none"
      activeClassName="sidebar-menu-item__accordion-current"
      exact={true}
      onClick={() => dispatch(hideAllMenu())}
    >
      {label}
      {badgeTemplate()}
    </NavLink>
  );
};
