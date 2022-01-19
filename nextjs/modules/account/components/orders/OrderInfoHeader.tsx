import React from "react";
import { useRouter } from "next/router";
import Link from "next/link";

interface IProps {
  orderNumber: string;
  orderId: number;
}

export const OrderInfoHeader: React.FC<IProps> = (props: IProps) => {
  const { orderNumber, orderId } = props;
  const router = useRouter();
  const headerItems = [
    {
      label: "Decisions required",
      path: "decisions-required",
    },
    {
      label: "Order tracking",
      path: "order-tracking",
    },
    {
      label: "Products ordered",
      path: "products-ordered",
    },
    {
      label: "Addresses and contacts",
      path: "addresses",
    },
    {
      label: "Order actions",
      path: "order-actions",
    },
    // TODO: Убрали поскольку не настроен бек под отправку и чтение писем
    // {
    //   label: "Order communication",
    //   path: "communication",
    // },
    {
      label: "Order log",
      path: "log",
    },
  ];

  return (
    <>
      <div className={"order-info-header-title"}>Order # {orderNumber}</div>
      <div className="order-info-header">
        {headerItems.map((item) => (
          <Link key={item.path} href={`/order/${orderId}/${item.path}`}>
            <a
              className={`order-info-header-item ${
                router.query.type === item.path &&
                "order-info-header-item-selected"
              }`}
            >
              {item.label}
            </a>
          </Link>
        ))}
      </div>
    </>
  );
};
