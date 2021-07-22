import React, { useRef, useState } from "react";
import { SidebarItemDto } from "@modules/account/ts/types/sidebar-item.type";
import { SideBarMenuAccordIonItem } from "./SideBarMenuAccordIonItem";
import { useAccordion } from "../../hooks/useAccordion";

interface sideBarMenuItemPropsDto extends SidebarItemDto {
  routerItems: SidebarItemDto[];
}

export const SideBarMenuAccordion: React.FC<sideBarMenuItemPropsDto> = ({
  to,
  label,
  routerItems,
}) => {
  const accordion = useAccordion();

  return (
    <React.Fragment>
      <div
        onClick={accordion.onItemClick}
        className={`sidebar-menu-container accordion ${
          accordion.open && "sidebar-menu-accordion-open"
        }`}
      >
        <div>{label}</div>
        <div
          className={`accordion-arrow ${
            accordion.open && "accordion-arrow-open"
          }`}
        />
      </div>
      <div
        ref={accordion.ref}
        style={{
          height: accordion.height,
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
