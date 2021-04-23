import React from "react";
import NavigateMenuItem from "./NavigateMenuItem";
import { HelpCenterItemDto } from "@/frontend/jsx/modules/help-center/ts/types";

const NavigateMenu: React.FC<HelpCenterItemDto> = ({ menuItems }) => {
  return (
    <nav className="navbar-wrap">
      {menuItems.map((item) => {
        return (
          <NavigateMenuItem
            activeIcon={item.activeIcon}
            key={item.id}
            link={item.items.route}
            text={item.title}
            icon={item.icon}
          />
        );
      })}
    </nav>
  );
};

export default NavigateMenu;
