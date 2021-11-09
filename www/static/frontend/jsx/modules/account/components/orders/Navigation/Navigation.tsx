import React from "react";
import Item from "@client/modules/account/components/orders/Navigation/Item";
import useBreakpoint from "@client/modules/account/hooks/useBreakpoint";
import { useSelector } from "react-redux";
import { AccountStore } from "@client/modules/account/ts/types/store.type";
import NavigationMobile from "@client/modules/account/components/orders/Navigation/NavigationMobile";

const Navigation: React.FC = () => {
  useSelector((store: AccountStore) => store.main.breakpoint);

  const menu = [
    {
      text: "Decisions required",
      path: "/",
      badge: 2,
      classes: {
        button: [
          "orders-navigation-button_theme_red_active",
          "orders-navigation-button_active",
        ],
      },
    },
    {
      text: "Order tracking",
      path: "/",
    },
    { text: "Products ordered", path: "/" },
    { text: "Addresses and contacts", path: "/" },
    { text: "Order actions", path: "/" },
    { text: "Order communication", path: "/" },
    { text: "Order log", path: "/" },
  ];

  const items = [];

  menu.forEach((value) => {
    items.push(<Item {...value} />);
  });

  const breakpoint = useBreakpoint();

  return breakpoint({
    md: <div className={"orders-navigation"}>{items}</div>,
    xs: (
      <NavigationMobile
        menu={menu}
        className={"account-orders__mobile-navigation"}
      />
    ),
  });
};

export default Navigation;
