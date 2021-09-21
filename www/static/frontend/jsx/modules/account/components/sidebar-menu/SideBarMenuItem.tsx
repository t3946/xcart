import React from "react";
import { NavLink } from "react-router-dom";
import classnames from "classnames";

interface sideBarMenuItemProps {
  to: string;
  label: string;
  badge?: number | string;
  className?: any;
  onClick?: any;
}

export const SideBarMenuItem: React.FC<sideBarMenuItemProps> = ({
  to,
  label,
  badge,
  className,
  onClick,
}) => {
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
      exact={true}
      activeClassName="active-route"
      className={classnames(
        "sidebar-menu-item text-decoration-none",
        className
      )}
      onClick={onClick}
    >
      {label}
      {badgeTemplate()}
    </NavLink>
  );
};
