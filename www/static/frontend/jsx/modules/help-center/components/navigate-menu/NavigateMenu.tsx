import React from "react";
import NavigateMenuItem from "./NavigateMenuItem";
import { HelpCenterItemDto } from "@/frontend/jsx/modules/help-center/ts/types";

const NavigateMenu: React.FC<HelpCenterItemDto> = ({ menuItems }) => {
  return (
    <nav className="navbar-wrap">
      {menuItems.map((item, id) => {
        const route = id === 0 ? "/help/" : `/help/${item.menu_id}`;
        return (
          <NavigateMenuItem
            activeIcon={item.active_icon}
            key={item.menu_id}
            link={route}
            text={item.title}
            icon={item.icon}
          />
        );
      })}
    </nav>
  );
};

export default NavigateMenu;
