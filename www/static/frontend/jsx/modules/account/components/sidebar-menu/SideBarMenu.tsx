import React from "react";
import { SideBarMenuAccordion } from "./SideBarMenuAccordIon";
import { SideBarMenuItem } from "./SideBarMenuItem";
import { logoutAction } from "../../../../redux/actions/account-actions/AutorizationActions";
import { useDispatch, useSelector } from "react-redux";
import { useHistory } from "react-router";
import { userClearAction } from "../../../../redux/actions/account-actions/UserActions";
import { StoreDto } from "@s3stores-mail/ts/types";

const SideBarMenu = () => {
  const dispatch = useDispatch();
  const history = useHistory();
  const user = useSelector((e: StoreDto) => e.user);
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
    {
      to: "/account/your-lists",
      label: "Your lists",
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
        form: { login: "vendor@s3stores.com", password: "123qwe" },
        callback: function () {
          dispatch(userClearAction());
          history.push("/account/login");
        },
      })
    );
  }

  return (
    <div className="sidebar-menu-wrapper">
      {menuItems.map((e) => {
        if (!e.routerItems) {
          return (
            <SideBarMenuItem
              to={e.to}
              label={e.label}
              className={"sidebar-menu__top-level-item"}
            />
          );
        }
        return (
          <SideBarMenuAccordion
            to={e.to}
            label={e.label}
            routerItems={e.routerItems}
            classes={{ handlerClass: "sidebar-menu__top-level-item" }}
          />
        );
      })}

      {user && (
        <button
          className={
            "form-button form-button__outline logout-button pt-2.5 pb-2.5 mt-4 rounded-0 w-100"
          }
          onClick={logout}
        >
          log out
        </button>
      )}
    </div>
  );
};

export default SideBarMenu;
