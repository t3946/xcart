import React from "react";
import Item from "@modules/account/components/orders/Navigation/Item";
// import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import StoreInterface from "@modules/account/ts/types/store.type";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { useRouter } from "next/router";

interface IProps {
  orderId: number;
}

const Navigation: React.FC<IProps> = ({ orderId }) => {
  const router = useRouter();
  const user = useSelectorAccount((e: StoreInterface) => e.user);

  const menu = [
    // {
    //   text: "Decisions required",
    //   path: "decisions-required",
    //   badge: user?.decisions_required_count || 0,
    // },
    {
      text: "Order tracking",
      path: "order-tracking",
    },
    { text: "Products ordered", path: "products-ordered" },
    { text: "Addresses and contacts", path: "addresses" },
    { text: "Order actions", path: "order-actions" },
    // TODO: Убрали поскольку не настроен бек под отправку и чтение писем
    // { text: "Order communication", path: `/order/${orderId}/` },
    { text: "Order log", path: `log` },
  ];

  // const breakpoint = useBreakpoint();

  //todo: fix breakpoint
  /*return breakpoint({
    md: <div className={"orders-navigation"}>{items}</div>,
    xs: (
      <NavigationMobile
        menu={menu}
        className={"account-orders__mobile-navigation"}
      />
    ),
  });*/
  return (
    <div className={"order-info-header p-0"}>
      {menu.map((value, index) => (
        <Item
          {...value}
          active={router.query.type === value.path}
          orderId={orderId}
          key={index}
        />
      ))}
    </div>
  );
};

export default Navigation;
