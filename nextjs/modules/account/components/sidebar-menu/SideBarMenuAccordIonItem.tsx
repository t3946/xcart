//todo: исправить опечатку в имени файла
import React from "react";
import { SidebarItem } from "@modules/account/ts/types/sidebar-item.type";
import Link from "next/link";
import { hideAllMenu } from "@redux/actions/account-actions/MenuActions";
import { useDispatch } from "react-redux";

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
      <span className="sidebar-badge sidebar-menu-item_badge d-flex align-items-center justify-content-center rounded-pill fw-bold">
        {badge}
      </span>
    );
  }

  //todo: у старого роутера был класс activeClassName="sidebar-menu-item__accordion-current"
  return (
    <Link href={to}>
      <a
        className="sidebar-menu-item sidebar-menu-item__accordion text-decoration-none"
        onClick={() => dispatch(hideAllMenu())}
      >
        {label}
        {badgeTemplate()}
      </a>
    </Link>
  );
};
