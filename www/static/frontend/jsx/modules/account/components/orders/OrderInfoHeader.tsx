import React from "react";
import { NavLink, useParams } from "react-router-dom";
import { OrderPageURLParams } from "@client/modules/account/ts/types/order-page-url-params.type";

interface OrderInfoHeaderProps {
  orderItem: any;
}

export const OrderInfoHeader: React.FC<OrderInfoHeaderProps> = ({
  orderItem,
}) => {
  const urlParams = useParams<OrderPageURLParams>();

  const headerItems = [
    {
      label: "Order tracking",
      to: `/account/orders/${urlParams.id}/${urlParams.orderType}/order-info/order-tracking`,
    },
    {
      label: "Products ordered",
      to: `/account/orders/${urlParams.id}/${urlParams.orderType}/order-info/products-ordered`,
    },
    {
      label: "Addresses and contacts",
      to: `/account/orders/${urlParams.id}/${urlParams.orderType}/order-info/addresses`,
    },
    {
      label: "Order actions",
      to: `/account/orders/${urlParams.id}/${urlParams.orderType}/order-info/order-actions`,
    },
    {
      label: "Order communication",
      to: `/account/orders/${urlParams.id}/${urlParams.orderType}/order-info/communication`,
    },
    {
      label: "Order log",
      to: `/account/orders/${urlParams.id}/${urlParams.orderType}/order-info/log`,
    },
  ];
  const headerTitle =
    orderItem.orderInfo.order_prefix + orderItem.orderInfo.orderid;
  return (
    <div>
      <div className={"order-info-header-title"}>Order # {headerTitle}</div>
      <div className="order-info-header">
        {headerItems.map((e) => {
          return (
            <NavLink
              activeClassName="order-info-header-item-selected"
              to={e.to}
              className="order-info-header-item"
            >
              {e.label}
            </NavLink>
          );
        })}
      </div>
    </div>
  );
};
