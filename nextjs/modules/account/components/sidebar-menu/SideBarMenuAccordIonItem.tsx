//todo: исправить опечатку в имени файла
import React from "react";
import { SidebarItem } from "@modules/account/ts/types/sidebar-item.type";
import { hideAllMenu } from "@redux/actions/account-actions/MenuActions";
import { useDispatch } from "react-redux";
import Badge from "@modules/components/Badge/Badge";
import ActiveLink from "@modules/components/ActiveLink/ActiveLink";
import cn from "classnames";
import Style from "@modules/account/components/sidebar-menu/SideBarMenuAccordIonItem.module.scss";

export const SideBarMenuAccordIonItem: React.FC<SidebarItem> = ({
  to,
  label,
  badge,
}) => {
  const dispatch = useDispatch();

  return (
    <ActiveLink href={to}>
      {(isActive) => {
        const classes = [
          "sidebar-menu-item",
          "sidebar-menu-item__accordion",
          "text-decoration-none",
          {
            [Style.item_active]: isActive,
            "cursor-default": isActive,
          },
        ];

        return (
          <a className={cn(classes)} onClick={() => dispatch(hideAllMenu())}>
            {label}

            {!!badge && (
              <Badge className={"sidebar-menu-item_badge"} text={badge} />
            )}
          </a>
        );
      }}
    </ActiveLink>
  );
};
