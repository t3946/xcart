import React from "react";
import { SideBarMenuAccordion } from "./SideBarMenuAccordIon";
import { SideBarMenuItem } from "./SideBarMenuItem";
import LogoutButton from "@modules/account/components/sidebar-menu/LogoutButton";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";

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
            <SideBarMenuItem
              to={value.to}
              label={value.label}
              badge={value.badge}
              className={"sidebar-menu_top-level-item"}
              onClick={value.onClick}
              key={index}
            />
          );
        }

        return (
          <SideBarMenuAccordion
            to={value.to}
            label={value.label}
            routerItems={value.routerItems}
            classes={{ handlerClass: "sidebar-menu_top-level-item" }}
            key={index}
          />
        );
      })}

      <LogoutButton classes={"d-lg-none"} />
    </div>
  );
};

export default SideBarMenu;
