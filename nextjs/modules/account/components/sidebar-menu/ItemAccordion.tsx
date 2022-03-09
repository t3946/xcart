import React from "react";
import { SidebarItem } from "@modules/account/ts/types/sidebar-item.type";
import { SideBarMenuAccordIonItem } from "./SideBarMenuAccordIonItem";
import { useAccordion } from "@modules/account/hooks/useAccordion";
import classnames from "classnames";
import ArrowIconTablet from "@modules/icon/components/account/chevron-down/AccountSidebarTablet";
import ArrowIconMobileDesktop from "@modules/icon/components/account/chevron-down/AccountSidebarMobileDesktop";
import StoreInterface from "@modules/account/ts/types/store.type";
import { useDispatch, useSelector } from "react-redux";
import { setMenuItemActiveAction } from "@redux/actions/account-actions/SideBarMenuActions";
import { useRouter } from "next/router";

import StylesItem from "@modules/account/components/sidebar-menu/Item.module.scss";

interface sideBarMenuItemPropsDto extends SidebarItem {
  to: string;
  routerItems: SidebarItem[];
  classes?: {
    handlerClass?: any;
  };
}

const ItemAccordion: React.FC<sideBarMenuItemPropsDto> = (
  props: Record<any, any>
) => {
  const router = useRouter();
  const { label, routerItems } = props;
  const initActive =
    useSelector(
      (e: StoreInterface) =>
        e.sidebar.menuItems.find((item) => item.to === props.to)?.active
    ) || router.asPath.indexOf(props.to) + 1;
  const dispatch = useDispatch();
  const accordion = useAccordion(300, !!initActive);

  const classes = {
    handlerClasses: [
      "accordion",
      { [StylesItem.item_accordion_opened]: accordion.open },
      props.classes.handlerClass,
    ],
    icon: [
      "accordion-arrow arrow-rotatable sidebar-menu-item_accordion-arrow",
      {
        "sidebar-accordion-arrow__open": accordion.open,
      },
    ],
    iconMobileDesktop: ["d-md-none", "d-lg-block"],
    iconTablet: ["d-none", "d-md-block", "d-lg-none"],
  };

  function iconTemplate(): any {
    return (
      <>
        <ArrowIconMobileDesktop
          className={classnames(classes.icon, classes.iconMobileDesktop)}
        />

        <ArrowIconTablet
          className={classnames(classes.icon, classes.iconTablet)}
        />
      </>
    );
  }

  return (
    <React.Fragment>
      <div
        onClick={() => {
          dispatch(setMenuItemActiveAction(props.to, !accordion.open));
          accordion.onItemClick();
        }}
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
          {routerItems.map((value: any, index: number) => {
            return (
              <SideBarMenuAccordIonItem
                to={value.to}
                label={value.label}
                badge={value.badge}
                key={index}
              />
            );
          })}
        </div>
      </div>
    </React.Fragment>
  );
};

export default ItemAccordion;
