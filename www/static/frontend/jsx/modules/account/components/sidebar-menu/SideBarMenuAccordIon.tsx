import React, { useRef, useState } from "react";
import { NavLink } from "react-router-dom";
import { SidebarItemDto } from "@modules/account/ts/sidebar-item.type";
import { SideBarMenuAccordIonItem } from "./SideBarMenuAccordIonItem";

interface sideBarMenuItemPropsDto extends SidebarItemDto {
  routerItems: SidebarItemDto[];
}

export const SideBarMenuAccordion: React.FC<sideBarMenuItemPropsDto> = ({
  to,
  label,
  routerItems,
}) => {
  const [height, setHeight] = useState(0);

  const [open, setOpen] = useState(false);

  const ref = useRef<HTMLDivElement>();

  const onItemClick = () => {
    if (!open) {
      setHeight(ref.current.scrollHeight);
    } else {
      setHeight(0);
    }
    setOpen(!open);
  };

  return (
    <React.Fragment>
      <div
        onClick={onItemClick}
        className={`sidebar-menu-container accordion ${
          open && "sidebar-menu-accordion-open"
        }`}
      >
        <div>{label}</div>
        <div className="accordion-arrow" />
      </div>
      <div
        ref={ref}
        style={{
          height,
          width: 190,
        }}
        className="sidebar-menu-accordion-content"
      >
        {routerItems.map((e) => {
          return (
            <SideBarMenuAccordIonItem to={`${to}/${e.to}`} label={e.label} />
          );
        })}
      </div>
    </React.Fragment>
  );
};
