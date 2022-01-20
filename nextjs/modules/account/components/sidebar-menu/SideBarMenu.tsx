import React from "react";
import { useRouter } from "next/router";
import Item from "@modules/account/components/sidebar-menu/Item";
import LogoutButton from "@modules/account/components/sidebar-menu/LogoutButton";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import StylesItem from "@modules/account/components/sidebar-menu/Item.module.scss";
import ItemAccordion from "@modules/account/components/sidebar-menu/ItemAccordion";
import Styles from "@modules/account/components/sidebar-menu/SideBarMenu.module.scss";
import { useDispatch } from "react-redux";
import { setMenuItemsAction } from "@redux/actions/account-actions/SideBarMenuActions";

const classes = {
  dropdownItem: [StylesItem.item_topLevel, StylesItem.item],
};

const SideBarMenu: React.FC = () => {
  const { asPath: activePath } = useRouter();
  const user = useSelectorAccount((e) => e.user);
  const dispatch = useDispatch();
  const menuItems = [
    { to: "/dashboard", label: "Dashboard" },
    {
      to: "/orders",
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

  React.useEffect(() => {
    let sidebar = [];
    for (const item of menuItems) {
      sidebar.push({ to: item.to, active: false });
    }
    dispatch(setMenuItemsAction({ menuItems: sidebar }));
  }, []);

  return (
    <div className={Styles.sidebarMenuWrapper}>
      {menuItems.map((value: Record<any, any>, index) => {
        if (!value.routerItems) {
          return (
            <Item
              to={value.to}
              label={value.label}
              badge={value.badge}
              className={classes.dropdownItem}
              onClick={value.onClick}
              key={index}
              active={value.to === activePath}
            />
          );
        }

        return (
          <ItemAccordion
            to={value.to}
            label={value.label}
            routerItems={value.routerItems}
            classes={{ handlerClass: classes.dropdownItem }}
            key={index}
          />
        );
      })}

      <LogoutButton classes={[...classes.dropdownItem, "d-lg-none"]} />
    </div>
  );
};

export default SideBarMenu;
