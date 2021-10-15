import React, { useState } from "react";
import { NavLink } from "react-router-dom";
import { useAccordion } from "@client/modules/account/hooks/useAccordion";

interface OrderItemProps {
  order: any;
  orderType: string;
}

export const OrderItem: React.FC<OrderItemProps> = ({ order, orderType }) => {
  const orderDate = new Date(Number(order.orderInfo.date)).toLocaleDateString(
    "en-EN",
    {
      month: "long",
      day: "2-digit",
      year: "numeric",
    }
  );

  const accordion = useAccordion(300);

  const [showAllItems, setShowAllItems] = useState(false);

  const shippingAddress =
    order.orderInfo.s_zipcode +
    " " +
    order.orderInfo.s_city +
    " " +
    order.orderInfo.s_address;

  const orderId = order.orderInfo.order_prefix + order.orderInfo.orderid;
  return (
    <div className="order-item-container">
      <div className="order-item-header-container">
        <div className="order-item-body-left-side header-left">
          <div>
            Order # <b>{orderId}</b>
          </div>
          <NavLink
            to={`/account/orders/${order.orderInfo.orderid}/${orderType}/order-info/order-tracking`}
          >
            <button className="form-button form-button__outline order-details-btn">
              order details
            </button>
          </NavLink>
        </div>
        <div className="order-item-body-right-side header-right">
          <div>
            <div>ORDER DATE</div>
            <div>{orderDate}</div>
          </div>
          <div>
            <div className="order-item-header-grand-total">GRAND TOTAL</div>
            <div className="order-item-header-grand-total">
              <b>US$ {order.orderInfo.total}</b>
            </div>
          </div>
        </div>
      </div>
      <div className="order-item-body-container">
        <div className="order-item-body-left-side">
          <div className="order-item-body-title">items ordered</div>

          <div className={"order-item-body-product-container"}>
            <div className="order-item-body-product-left-part">
              <img
                className="order-item-body-product-img"
                src="https://img2.wtftime.ru/store/2020/11/19/EYGJdrlu_amp_big.jpg"
              />
              <div>
                <a className="order-item-body-product-name">
                  Ecstasy Crafts Architextures Treasures - Wooden Corkscrew
                </a>
                <div className="order-item-body-product-sku">ECS-7G25093</div>
              </div>
            </div>
            <div className="order-item-body-product-right-part-text">x 3</div>
          </div>

          <div
            style={{
              height: accordion.height,
            }}
            ref={accordion.ref}
            className="order-items-list"
          >
            {order.orderGroups.map((group, groupIndex) => {
              return group.orderGroupsItems.map((item, itemIndex) => {
                if (groupIndex === 0 && itemIndex === 0) {
                  return null;
                }
                return (
                  <div className={"order-item-body-product-container"}>
                    <div className="order-item-body-product-left-part">
                      <img
                        className="order-item-body-product-img"
                        src="https://img2.wtftime.ru/store/2020/11/19/EYGJdrlu_amp_big.jpg"
                      />
                      <div>
                        <a className="order-item-body-product-name">
                          Ecstasy Crafts Architextures Treasures - Wooden
                          Corkscrew
                        </a>
                        <div className="order-item-body-product-sku">
                          ECS-7G25093
                        </div>
                      </div>
                    </div>
                    <div className="order-item-body-product-right-part-text">
                      x 3
                    </div>
                  </div>
                );
              });
            })}
          </div>

          {order.orderGroups.length > 1 ||
            (order.orderGroups[0].orderGroupsItems.length > 1 && (
              <button
                onClick={() => {
                  accordion.onItemClick();
                  setShowAllItems(!showAllItems);
                }}
                className="form-button form-button__outline"
              >
                {!showAllItems ? "show more" : "hide"}
              </button>
            ))}
        </div>
        <div className="order-item-body-right-side">
          <div className="order-item-body-title">Shipping address</div>
          <div>{shippingAddress}</div>
        </div>
      </div>
    </div>
  );
};
