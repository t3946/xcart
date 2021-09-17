import React from "react";
import { SideBarMenuAccordion } from "./SideBarMenuAccordIon";
import { SideBarMenuItem } from "./SideBarMenuItem";
import { logoutAction } from "@client/jsx/redux/actions/account-actions/AutorizationActions";
import { useDispatch, useSelector } from "react-redux";
import { useHistory } from "react-router";
import { userClearAction } from "@client/jsx/redux/actions/account-actions/UserActions";
import { StoreDto } from "@s3stores-mail/ts/types";
import { setIsList } from "@client/jsx/redux/actions/account-actions/MainActions";
import { route } from "@client/jsx/utils/AppData";
import useBreakpoint from "@client/modules/account/hooks/useBreakpoint";

const SideBarMenu: React.FC = () => {
  const dispatch = useDispatch();
  const history = useHistory();
  const user = useSelector((e: StoreDto) => e.user);
  const breakpoint = useBreakpoint();
  const menuItems = [
    { to: "/account/dashboard", label: "Dashboard" },
    {
      to: "",
      label: "Orders",
      routerItems: [
        { to: route("account:orders"), label: "Decisions required", badge: 2 },
        { to: route("account:orders"), label: "Open orders" },
        { to: route("account:orders"), label: "Cancelled orders" },
        { to: route("account:orders"), label: "Completed orders" },
        { to: route("account:orders"), label: "Buy again" },
      ],
    },
    {
      to: "/account/your-lists",
      label: "Shopping Lists",
      onClick: () => dispatch(setIsList(true)),
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

  function logout() {
    dispatch(
      logoutAction({
        callback: function () {
          dispatch(userClearAction());
          history.push("/account/login");
        },
      })
    );
  }

  function logoutButtonTemplate(): any {
    if (!user) {
      return;
    }

    return breakpoint({
      xs: (
        <button
          className={
            "sidebar-menu-item sidebar-menu_top-level-item text-start w-100 sidebar-menu-item__logout"
          }
          onClick={logout}
        >
          Log out
        </button>
      ),

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
