import React, { useRef, useState } from "react";
import { SidebarItemDto } from "@modules/account/ts/types/sidebar-item.type";
import { SideBarMenuAccordIonItem } from "./SideBarMenuAccordIonItem";
import { useAccordion } from "../../hooks/useAccordion";
import classNames from "classnames";

interface sideBarMenuItemPropsDto extends SidebarItemDto {
  routerItems: SidebarItemDto[];
  classes?: {
    handlerClass?: any;
  };
}

export const SideBarMenuAccordion: React.FC<sideBarMenuItemPropsDto> = ({
  to,
  label,
  routerItems,
  classes,
}) => {
  const accordion = useAccordion();
  const handlerClasses = [
    "sidebar-menu-container accordion",
    { "sidebar-menu-accordion-open": accordion.open },
    classes.handlerClass,
  ];

  return (
    <React.Fragment>
      <div onClick={accordion.onItemClick} className={classNames(handlerClasses)}>
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
