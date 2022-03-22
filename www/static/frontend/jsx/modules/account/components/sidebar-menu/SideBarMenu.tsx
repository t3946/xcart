import React from "react";
import { SideBarMenuAccordion } from "./SideBarMenuAccordIon";
import { SideBarMenuItem } from "./SideBarMenuItem";
import { useSelector } from "react-redux";
import { route } from "@client/jsx/utils/AppData";
import LogoutButton from "@client/modules/account/components/sidebar-menu/LogoutButton";
import useBreakpoint from "@client/modules/account/hooks/useBreakpoint";
import StoreInterface from "@client/modules/account/ts/types/store.type";
import cn from "classnames";

import Styles from "@client/jsx/modules/account/components/sidebar-menu/SideBarMenu.module.scss";

interface IProps {
  classes?: {
    item?: any;
  };
  showLogout?: boolean;
}

const SideBarMenu: React.FC<IProps> = (props) => {
  const { showLogout = false } = props;
  const breakpoint = useBreakpoint();
  const user = useSelector((e: StoreInterface) => e.user);
  const menuItems = [
    { to: "/account/dashboard", label: "Dashboard" },
    {
      to: "",
      label: "Orders",
      routerItems: [
        // {
        //   to: route("account:order-decisions-required"),
        //   label: "Decisions required",
        //   badge: user?.decisions_required_count || 0,
        // },
        { to: "/account/orders/open-orders", label: "Open orders" },
        { to: "/account/orders/canceled-orders", label: "Cancelled orders" },
        { to: "/account/orders/completed-orders", label: "Completed orders" },
        { to: "/account/orders/buy-again", label: "Buy again" },
      ],
    },
    {
      to: "/account/shopping-lists",
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
  ];

  function logoutButtonTemplate(): any {
    if (!user) {
      return;
    }

    return <LogoutButton />;
  }

  return (
    <div className={cn("sidebar-menu-wrapper")}>
      <div className={Styles.sidebarMenuWrapper}>
        {menuItems.map((value: Record<any, any>) => {
          if (!value.routerItems) {
            return (
              <SideBarMenuItem
                to={value.to}
                label={value.label}
                badge={value.badge}
                className={["sidebar-menu_top-level-item", props.classes?.item]}
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
    </div>
  );
};

export default SideBarMenu;
