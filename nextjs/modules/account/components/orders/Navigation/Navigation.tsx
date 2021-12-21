import React from "react";
import Item from "@modules/account/components/orders/Navigation/Item";
// import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import StoreInterface from "@modules/account/ts/types/store.type";
import NavigationMobile from "@modules/account/components/orders/Navigation/NavigationMobile";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import Styles from "@modules/account/components/orders/Navigation/Navigation.module.scss"

const Navigation: React.FC = () => {
  // useSelector((store: AccountStore) => store.main.breakpoint);
  const user = useSelectorAccount((e: StoreInterface) => e.user);

  const menu = [
    {
      text: "Decisions required",
      path: "/orders/decisions-required",
      badge: user?.decisions_required_count || 0,
      classes: {
        button: [
          Styles.ordersNavigationButton_active,
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

  menu.forEach((value, index) => {
    items.push(<Item {...value} key={index} />);
  });

  // const breakpoint = useBreakpoint();

  //todo: fix breakpoint
  /*return breakpoint({
    md: <div className={"orders-navigation"}>{items}</div>,
    xs: (
      <NavigationMobile
        menu={menu}
        className={"account-orders__mobile-navigation"}
      />
    ),
  });*/
  return <div className={"orders-navigation"}>{items}</div>;
};

export default Navigation;
