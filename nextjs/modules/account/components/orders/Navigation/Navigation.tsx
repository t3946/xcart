import React from "react";
import Item from "@modules/account/components/orders/Navigation/Item";
// import useBreakpoint from "@modules/account/hooks/useBreakpoint";
import StoreInterface from "@modules/account/ts/types/store.type";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import {useRouter} from "next/router";

interface IProps {
  orderId: number;
  orderStatus: string;
}

const Navigation: React.FC<IProps> = ({ orderId, orderStatus }) => {
  const router = useRouter();
  const user = useSelectorAccount((e: StoreInterface) => e.user);
  const menu = [
    // {
    //   text: "Decisions required",
    //   path: "/orders/decisions-required",
    //   badge: user?.decisions_required_count || 0,
    // },
    {
      text: "Order tracking",
      path: `/order/${orderId}/order-tracking`,
      isVisible: !["D", "A", "F"].includes(orderStatus),
    },
    {
      text: "Products ordered",
      path: `/order/${orderId}/products-ordered`,
    },
    {
      text: "Addresses and contacts",
      path: `/order/${orderId}/addresses`,
    },
    {
      text: "Order actions",
      path: `/order/${orderId}/order-actions`,
    },
    // TODO: Убрали поскольку не настроен бек под отправку и чтение писем
    // { text: "Order communication", path: `/order/${orderId}/` },
    {
      text: "Order log",
      path: `/order/${orderId}/log`,
    },
  ];

  return (
    <div className={"order-info-header p-0"}>
      {menu.map((value, index) => {
        if (value.isVisible === false) {
          return null;
        }

        return (
          <Item
            {...value}
            active={router.query.type === value.path}
            orderId={orderId}
            key={index}
          />
        );
      })}
    </div>
  );
};

export default Navigation;
