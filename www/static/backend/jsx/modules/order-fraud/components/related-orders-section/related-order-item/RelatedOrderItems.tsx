import React, { Fragment } from "react";
import {
  RelatedOrderItem,
  RelatedOrderType,
} from "@admin/modules/order-fraud/ts/types/redux";
interface RelatedOrderItems {
  orders: RelatedOrderItem[];
  type: RelatedOrderType;
}
export const RelatedOrderItems: React.FC<RelatedOrderItems> = ({
  orders,
  type,
}) => {
  const getSeparator = (index: number) => {
    if (orders.filter((item) => item.type === type).length === index + 1) {
      return " ";
    }
    return ", ";
  };
  return (
    <td>
      {orders
        .filter((item) => item.type === type)
        .map((order, index) => (
          <Fragment>
            <a
              href={`/admin/order.php?orderid=${order.orderId}`}
              target="_blank"
              className="order-link"
            >
              {order.prefix}
            </a>
            {getSeparator(index)}
          </Fragment>
        ))}
    </td>
  );
};
