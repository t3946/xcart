import React from "react";
import { SideBarMenuAccordion } from "./SideBarMenuAccordIon";
import { SideBarMenuItem } from "./SideBarMenuItem";
import { useSelector } from "react-redux";
import LogoutButton from "@modules/account/components/sidebar-menu/LogoutButton";
import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import StoreInterface from "@modules/account/ts/types/store.type";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";

const SideBarMenu: React.FC = () => {
  const routes = useSelectorAccount((e) => e.routes);
  const breakpoint = useBreakpoint();
  const user = useSelector((e: StoreInterface) => e.user);
  const menuItems = [
    { to: "/account/dashboard", label: "Dashboard" },
    {
      to: "",
      label: "Orders",
      routerItems: [
        {
          to: "/orders/open-orders/decisions-required",
          label: "Decisions required",
          badge: user?.decisions_required_count || 0,
        },
        { to: "/account/orders/open-orders", label: "Open orders" },
        { to: "/account/orders/canceled-orders", label: "Cancelled orders" },
        { to: "/account/orders/completed-orders", label: "Completed orders" },
        { to: "/orders", label: "Buy again" },
      ],
    },
    {
      to: "/account/your-lists",
      label: "Shopping Lists",
    },
    { to: "/account/addresses", label: "Addresses" },
    {
      to: "/account/payments",
      label: "Payments",
      routerItems: [
        { to: "/account/payments/wallet", label: "Wallet" },
        { to: "/account/payments/transactions", label: "Transactions" },
      ],
    },
    { to: "/account/login-and-security", label: "Login & security" },
    { to: "/account/public-profile", label: "Public profile" },
    { to: "/account/rewards", label: "Rewards" },
  ];

  function logoutButtonTemplate(): any {
    if (!user) {
      return;
    }

    return breakpoint({
      xs: <LogoutButton />,
      lg: null,
    });
  }

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

      {logoutButtonTemplate()}
    </div>
  );
};

export default SideBarMenu;
