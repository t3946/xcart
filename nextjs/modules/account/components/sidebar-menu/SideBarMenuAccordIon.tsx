import React from "react";
import { SidebarItem } from "@modules/account/ts/types/sidebar-item.type";
import { SideBarMenuAccordIonItem } from "./SideBarMenuAccordIonItem";
import { useAccordion } from "../../hooks/useAccordion";
import classnames from "classnames";
import ArrowIconTablet from "@modules/icon/components/account/chevron-down/AccountSidebarTablet";
import ArrowIconMobileDesktop from "@modules/icon/components/account/chevron-down/AccountSidebarMobileDesktop";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";

interface sideBarMenuItemPropsDto extends SidebarItem {
  routerItems: SidebarItem[];
  classes?: {
    handlerClass?: any;
  };
}

export const SideBarMenuAccordion: React.FC<sideBarMenuItemPropsDto> = (
  props: Record<any, any>
) => {
  const { to, label, routerItems } = props;
  const accordion = useAccordion();
  const breakpoint = useBreakpoint();

  const classes = {
    handlerClasses: [
      "sidebar-menu-item accordion",
      { "sidebar-menu-item__opened-accordion": accordion.open },
      props.classes.handlerClass,
    ],
    iconClasses: [
      "accordion-arrow arrow-rotatable sidebar-menu-item_accordion-arrow",
      {
        "sidebar-accordion-arrow__open": accordion.open,
      },
    ],
  };

  function iconTemplate(): any {
    return breakpoint({
      xs: (
        <ArrowIconMobileDesktop className={classnames(classes.iconClasses)} />
      ),
      md: <ArrowIconTablet className={classnames(classes.iconClasses)} />,
      lg: (
        <ArrowIconMobileDesktop className={classnames(classes.iconClasses)} />
      ),
    });
  }

  return (
    <React.Fragment>
      <div
        onClick={accordion.onItemClick}
        className={classnames(classes.handlerClasses)}
      >
        <div>{label}</div>
        {iconTemplate()}
      </div>

      <div
        ref={accordion.ref}
        style={{
          height: accordion.height,
        }}
        className={"overflow-hidden common-transition"}
      >
        <div className="sidebar-menu-accordion-content">
          {routerItems.map((value) => {
            return (
              <SideBarMenuAccordIonItem
                to={value.to}
                label={value.label}
                badge={value.badge}
              />
            );
          })}
        </div>
      </div>
    </React.Fragment>
  );
};
