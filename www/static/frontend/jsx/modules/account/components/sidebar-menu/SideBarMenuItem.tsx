import React from "react";
import { NavLink } from "react-router-dom";
import classnames from "classnames";

interface sideBarMenuItemPropsDto {
  to: string;
  label: string;
  badge?: number | string;
  className?: any;
  onClick?: any;
}

export const SideBarMenuItem: React.FC<sideBarMenuItemPropsDto> = ({
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

  if (document.location.pathname.indexOf("/account") !== -1) {
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
  } else {
    return (
      <a
        href={to}
        className={classnames(
          "sidebar-menu-item text-decoration-none",
          className
        )}
        onClick={onClick}
      >
        {label}
        {badgeTemplate()}
      </a>
    );
  }
};
