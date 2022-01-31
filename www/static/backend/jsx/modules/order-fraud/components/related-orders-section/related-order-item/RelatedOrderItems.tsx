import React, { Fragment } from "react";
import { RelatedOrderItem } from "@admin/modules/order-fraud/ts/types/redux";
interface RelatedOrderItems {
  orders: RelatedOrderItem[];
  isFraud?: boolean;
}
export const RelatedOrderItems: React.FC<RelatedOrderItems> = ({
  orders,
  isFraud = false,
}) => {
  const getSeparator = (index: number) => {
    if (
      orders.filter((item) => item.isFraud === isFraud).length ===
      index + 1
    ) {
      return " ";
    }
    return ", ";
  };
  return (
    <td>
      {orders
        .filter((item) => item.isFraud === isFraud)
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
