import React from "react";
import { useBreakPoint } from "../../hooks/useBreakPoint";
import { BreadCrumbs } from "./BreadCrumbs";
import { SideBarMenuAccordion } from "./SideBarMenuAccordIon";
import { SideBarMenuItem } from "./SideBarMenuItem";

export const SideBarMenu = () => {
  const breakpoints = useBreakPoint();
  const menuItems = [
    { to: "/account/dashboard", label: "Dashboard" },
    {
      to: "/account/orders",
      label: "Orders",
      routerItems: [
        { to: "my-orders", label: "My orders" },
        { to: "buy-again", label: "Buy again" },
        { to: "open-orders", label: "Open orders" },
        { to: "cancelled-orders", label: "Cancelled orders" },
      ],
    },
    { to: "/account/your-lists", label: "Your lists" },
    { to: "/account/addresses", label: "Addresses" },
    {
      to: "/account/payments",
      label: "Payments",
      routerItems: [
        { to: "wallet", label: "Wallet" },
        { to: "transactions", label: "Transactions" },
      ],
    },
    { to: "/account/login-security", label: "Login & security" },
    { to: "/account/public-profile", label: "Public profile" },
    { to: "/account/rewards", label: "Rewards" },
  ];
  const routerItems = [
    {
      to: "12",
      label: "123",
    },
    {
      to: "13",
      label: "123",
    },
    {
      to: "14",
      label: "123",
    },
    {
      to: "15",
      label: "123",
    },
    {
      to: "16",
      label: "123",
    },
  ];
  return (
    <div className="sidebar-menu-wrapper">
      {menuItems.map((e) => {
        if (!e.routerItems) {
          return <SideBarMenuItem to={e.to} label={e.label} />;
        }
        return (
          <SideBarMenuAccordion
            to={e.to}
            label={e.label}
            routerItems={e.routerItems}
          />
        );
      })}
    </div>
  );
};
