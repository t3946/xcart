import React from "react";
import { useRouter } from "next/router";
import Link from "next/link";

interface OrderInfoHeaderProps {
  orderNumber?: string;
  orderId: number;
}

export const OrderInfoHeader: React.FC<OrderInfoHeaderProps> = ({
  orderNumber = "",
  orderId,
}) => {
  const router = useRouter();
  const headerItems = [
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
    {
      label: "Order communication",
      path: "communication",
    },
    {
      label: "Order log",
      path: "log",
    },
  ];
  return (
    <div>
      <div className={"order-info-header-title"}>Order #{orderNumber}</div>
      <div className="order-info-header">
        {headerItems.map((item) => (
          <Link href={`/order/${orderId}/${item.path}`}>
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
    </div>
  );
};
