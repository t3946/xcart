import React from "react";
import NavigateMenuItem from "./NavigateMenuItem";

const NavigateMenu: React.FC<any> = ({ items }) => {
  return (
    <nav className="navbar-wrap">
      {items.map((item) => {
        return (
          <NavigateMenuItem
            key={item.items.id}
            link={item.items.route}
            text={item.title}
            image={""}
          />
        );
      })}
    </nav>
  );
};

export default NavigateMenu;
