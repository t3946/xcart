import React from "react";
import { NavLink } from "react-router-dom";
import classNames from "classnames";
interface sideBarMenuItemProps {
  to: string;
  label: string;
  className?: any;
  onClick?: any;
}

export const SideBarMenuItem: React.FC<sideBarMenuItemProps> = ({
  to,
  label,
  className,
  onClick,
}) => {
  return (
    <NavLink
      to={to}
      exact={true}
      activeClassName="active-route"
      className={classNames("sidebar-menu-container", className)}
      onClick={onClick}
    >
      <span>{label}</span>
    </NavLink>
  );
};
