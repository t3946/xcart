import React from "react";
import Item from "@modules/account/components/sidebar-menu/Item";
import LogoutButton from "@modules/account/components/sidebar-menu/LogoutButton";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import Styles from "@modules/account/components/sidebar-menu/Item.module.scss";
import ItemAccordion from "@modules/account/components/sidebar-menu/ItemAccordion";
import cn from "classnames";

const SideBarMenu: React.FC = () => {
  const user = useSelectorAccount((e) => e.user);
  const menuItems = [
    { to: "/dashboard", label: "Dashboard" },
    {
      to: "",
      label: "Orders",
      routerItems: [
        {
          to: "/orders/decisions-required",
          label: "Decisions required",
          badge: user?.decisions_required_count || 0,
        },
        { to: "/orders/open-orders", label: "Open orders" },
        { to: "/orders/canceled-orders", label: "Cancelled orders" },
        { to: "/orders/completed-orders", label: "Completed orders" },
        { to: "/orders", label: "Buy again" },
      ],
    },
    {
      to: "/shopping-lists",
      label: "Shopping Lists",
    },
    { to: "/addresses", label: "Addresses" },
    {
      to: "/payments",
      label: "Payments",
      routerItems: [
        { to: "/payments/wallet", label: "Wallet" },
        { to: "/payments/transactions", label: "Transactions" },
      ],
    },
    { to: "/login-and-security", label: "Login & security" },
    { to: "/public-profile", label: "Public profile" },
    { to: "/rewards", label: "Rewards" },
  ];

  return (
    <div className="sidebar-menu-wrapper">
      {menuItems.map((value: Record<any, any>, index) => {
        if (!value.routerItems) {
          return (
            <Item
              to={value.to}
              label={value.label}
              badge={value.badge}
              className={Styles.item_topLevel}
              onClick={value.onClick}
              key={index}
            />
          );
        }

        return (
          <ItemAccordion
            to={value.to}
            label={value.label}
            routerItems={value.routerItems}
            classes={{ handlerClass: Styles.item_topLevel }}
            key={index}
          />
        );
      })}

      <LogoutButton classes={"d-lg-none"} />
    </div>
  );
};

export default SideBarMenu;
