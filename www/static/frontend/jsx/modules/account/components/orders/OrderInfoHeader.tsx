import React from "react";
import { NavLink } from "react-router-dom";
import { headerItems } from "@client/modules/account/ts/consts/header-items";

interface OrderInfoHeaderProps {
  orderItem: any;
}

export const OrderInfoHeader: React.FC<OrderInfoHeaderProps> = ({
  orderItem,
}) => {
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
