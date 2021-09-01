import React from "react";
import { NavLink } from "react-router-dom";
import { NavigateMenuItemDto } from "@/frontend/jsx/modules/help-center/ts/types";
import { useLocation } from "react-router-dom";

const NavigateMenuItem: React.FC<NavigateMenuItemDto> = ({
  link,
  text,
  icon,
  activeIcon,
}) => {
  const location = useLocation();
  return (
    <NavLink
      exact={true}
      className="navigate-item"
      activeClassName="navigate-item active"
      to={link}
    >
      <div className="navigate-item-image-wrap">
        <img
          className="navigate-item-image"
          src={location.pathname === link ? activeIcon : icon}
        />
      </div>
      <span className="navigate-item-text">{text}</span>
    </NavLink>
  );
};

export default NavigateMenuItem;
