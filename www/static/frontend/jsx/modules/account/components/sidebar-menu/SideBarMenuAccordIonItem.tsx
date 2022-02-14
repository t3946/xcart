import React from "react";
import { SidebarItem } from "@client/jsx/modules/account/ts/types/sidebar-item.type";
import { NavLink } from "react-router-dom";
import { hideAllMenu } from "@client/jsx/redux/actions/account-actions/MenuActions";
import { useDispatch } from "react-redux";
import cn from "classnames";

import Styles from "@client/jsx/modules/account/components/sidebar-menu/SideBarMenuAccordIonItem.module.scss";

export const SideBarMenuAccordIonItem: React.FC<SidebarItem> = ({
  to,
  label,
  badge,
}) => {
  const dispatch = useDispatch();

  function badgeTemplate(): any {
    if (!badge) {
      return;
    }

    return (
      <span
        className={cn(
          "sidebar-badge",
          "d-flex",
          "align-items-center",
          "justify-content-center",
          "fw-bold",
          "sidebar-menu-item_badge",
          Styles.badge
        )}
      >
        {badge}
      </span>
    );
  }

  if (document.location.pathname.indexOf("/account") !== -1) {
    return (
      <NavLink
        to={to}
        className={cn(
          Styles.accordionItem,
          "sidebar-menu-item",
          "sidebar-menu-item__accordion",
          "text-decoration-none"
        )}
        activeClassName="sidebar-menu-item__accordion-current"
        exact={true}
        onClick={() => dispatch(hideAllMenu())}
      >
        {label}
        {badgeTemplate()}
      </NavLink>
    );
  } else {
    return (
      <a
        href={to}
        className={cn(
          Styles.accordionItem,
          "sidebar-menu-item",
          "sidebar-menu-item__accordion",
          "text-decoration-none"
        )}
        onClick={() => dispatch(hideAllMenu())}
      >
        {label}
        {badgeTemplate()}
      </a>
    );
  }
};
