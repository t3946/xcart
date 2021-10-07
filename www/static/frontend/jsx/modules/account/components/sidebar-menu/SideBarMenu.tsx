import React from "react";
import { SideBarMenuAccordion } from "./SideBarMenuAccordIon";
import { SideBarMenuItem } from "./SideBarMenuItem";
import { useSelector } from "react-redux";
import { route } from "@client/jsx/utils/AppData";
import LogoutButton from "@client/modules/account/components/sidebar-menu/LogoutButton";
import useBreakpoint from "@client/modules/account/hooks/useBreakpoint";
import { AccountStore } from "@client/modules/account/ts/types/account-store.type";

const SideBarMenu: React.FC = () => {
  const breakpoint = useBreakpoint();
  const user = useSelector((e: AccountStore) => e.user);
  const menuItems = [
    { to: "/account/dashboard", label: "Dashboard" },
    {
      to: "",
      label: "Orders",
      routerItems: [
        { to: "account/orders", label: "Decisions required", badge: 2 },
        { to: "account/orders/open-orders", label: "Open orders" },
        { to: "account/orders/canceled-orders", label: "Cancelled orders" },
        { to: "account/orders/completed-orders", label: "Completed orders" },
        { to: route("account:orders"), label: "Buy again" },
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
        { to: "wallet", label: "Wallet" },
        { to: "transactions", label: "Transactions" },
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
      {menuItems.map((value: Record<any, any>) => {
        if (!value.routerItems) {
          return (
            <SideBarMenuItem
              to={value.to}
              label={value.label}
              badge={value.badge}
              className={"sidebar-menu_top-level-item"}
              onClick={value.onClick}
            />
          );
        }

        return (
          <SideBarMenuAccordion
            to={value.to}
            label={value.label}
            routerItems={value.routerItems}
            classes={{ handlerClass: "sidebar-menu_top-level-item" }}
          />
        );
      })}

      {logoutButtonTemplate()}
    </div>
  );
};

export default SideBarMenu;
