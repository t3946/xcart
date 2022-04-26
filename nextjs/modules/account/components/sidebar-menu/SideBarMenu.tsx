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
import { hideAllMenu } from "@redux/actions/account-actions/MenuActions";
import Head from "next/head";

const classes = {
  dropdownItem: [StylesItem.item_topLevel, StylesItem.item],
};

interface IProps {
  classes?: {
    item?: any;
  };
  showLogout?: boolean;
}

const SideBarMenu: React.FC<IProps> = (props) => {
  const { showLogout = false } = props;
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
          isVisible: !!user?.decisions_required_count,
        },
        { to: "/orders/open-orders", label: "Open orders" },
        { to: "/orders/canceled-orders", label: "Canceled orders" },
        { to: "/orders/completed-orders", label: "Completed orders" },
        { to: "/orders/buy-again", label: "Buy again" },
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
  ];

  React.useEffect(() => {
    const sidebar = [];
    for (const item of menuItems) {
      sidebar.push({ to: item.to, active: false });
    }
    dispatch(setMenuItemsAction({ menuItems: sidebar }));
  }, []);

  function getTitle(): string {
    for (const item of menuItems) {
      const activePage = item?.routerItems?.find(
        (childItem) => childItem.to == activePath
      )?.label;
      if (activePage) {
        return activePage;
      }
      if (item.to === activePath) return item.label;
    }

    return "";
  }

  return (
    <>
      <Head>
        <title>{getTitle()}</title>
      </Head>
      <div className={Styles.sidebarMenuWrapper}>
        {menuItems.map((value: Record<any, any>, index) => {
          if (value.isVisible === false) {
            return null;
          }

          if (!value.routerItems) {
            return (
              <Item
                to={value.to}
                label={value.label}
                badge={value.badge}
                className={[classes.dropdownItem, props.classes?.item]}
                onClick={() => {
                  dispatch(hideAllMenu());
                }}
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

        <LogoutButton
          classes={[
            ...classes.dropdownItem,
            "d-md-block",
            { "d-none": !user, "d-lg-none": !showLogout },
          ]}
        />
      </div>
    </>
  );
};

export default SideBarMenu;
