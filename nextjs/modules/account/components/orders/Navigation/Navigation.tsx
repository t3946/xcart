import React from "react";
import Item from "@modules/account/components/orders/Navigation/Item";
import StoreInterface from "@modules/account/ts/types/store.type";
import NavigationMobile from "@modules/account/components/orders/Navigation/NavigationMobile";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";

const Navigation: React.FC = () => {
  useSelectorAccount((e) => e.main.breakpoint);
  const user = useSelectorAccount((e: StoreInterface) => e.user);

  const menu = [
    {
      text: "Decisions required",
      path: "/orders/decisions-required",
      badge: user?.decisions_required_count || 0,
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

  const items: any[] = [];

  menu.forEach((value, index) => {
    items.push(<Item {...value} key={index} />);
  });

  return (
    <>
      <NavigationMobile
        menu={menu}
        className={"account-orders__mobile-navigation d-lg-none"}
      />

      <div className={"orders-navigation d-none d-lg-flex"}>{items}</div>
    </>
  );
};

export default Navigation;
