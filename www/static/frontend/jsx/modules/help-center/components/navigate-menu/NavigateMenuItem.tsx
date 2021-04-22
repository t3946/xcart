import React from "react";
import { NavLink } from "react-router-dom";
import { NavigateMenuItemDto } from "@/frontend/jsx/modules/help-center/ts/types";

const NavigateMenuItem: React.FC<NavigateMenuItemDto> = ({
  link,
  image,
  text,
}) => {
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
          src="/static/frontend/images/icons/forms/checkmark_accepted.svg"
        />
      </div>
      <span className="navigate-item-text">{text}</span>
    </NavLink>
  );
};

export default NavigateMenuItem;
