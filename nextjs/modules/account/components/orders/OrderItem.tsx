import React, { useState } from "react";
import { NavLink } from "react-router-dom";
import { useAccordion } from "@client/modules/account/hooks/useAccordion";
import useBreakpoint from "@client/modules/account/hooks/useBreakpoint";
import { useSelector } from "react-redux";
import { AccountStore } from "@client/modules/account/ts/types/store.type";

interface OrderItemProps {
  order: any;
  orderType: string;
  orderItem: any;
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

  const accordion = useAccordion(200);

  const [showAllItems, setShowAllItems] = useState(false);

  const shippingAddress =
    order.orderInfo.s_zipcode +
    " " +
    order.orderInfo.s_city +
    " " +
    order.orderInfo.s_address;

  const orderId = order.orderInfo.order_prefix + order.orderInfo.orderid;

  const breakPoint = useBreakpoint();
  return (
    <div className="order-item-container">
      <div className="order-item-header-container">
        <div className="order-item-body-left-side header-left">
          <div className="order-item-name">
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
          <div className="order-item-body-right-side">
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
          <div className="order-item-body-title">items ordered</div>

          <div className={"order-item-body-product-container"}>
            <div className="order-item-body-product-left-part">
              <img
                className="order-item-body-product-img"
                src={order.orderGroups[0].orderGroupsItems[0].image}
              />
              <div>
                <a className="order-item-body-product-name">
                  {order.orderGroups[0].orderGroupsItems[0].product}
                </a>
                <div className="order-item-body-product-sku">
                  {order.orderGroups[0].orderGroupsItems[0].productcode}
                </div>
              </div>
            </div>
            <div className="order-item-body-product-right-part-text">
              x {order.orderGroups[0].orderGroupsItems[0].amount}
            </div>
          </div>

          <div
            style={{
              height: accordion.height,
            }}
            ref={accordion.ref}
            className="order-items-list"
          >
            {order.orderGroups.map((group, groupIndex) => {
              return group.orderGroupsItems.map((groupItem, itemIndex) => {
                if (groupIndex === 0 && itemIndex === 0) {
                  return null;
                }
                return (
                  <div className={"order-item-body-product-container"}>
                    <div className="order-item-body-product-left-part">
                      <img
                        className="order-item-body-product-img"
                        src={groupItem.image}
                      />
                      <div>
                        <a className="order-item-body-product-name">
                          {groupItem.product}
                        </a>
                        <div className="order-item-body-product-sku">
                          {groupItem.productcode}
                        </div>
                      </div>
                    </div>
                    <div className="order-item-body-product-right-part-text">
                      x {groupItem.amount}
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
                className="form-button form-button__outline order-item-show-btn"
              >
                {!showAllItems ? "show more" : "hide"}
              </button>
            ))}
        </div>
        <div className="order-item-body-right-side order-item-address-container">
          <div className="order-item-body-title address-title">
            Shipping address
          </div>
          <div className="order-item-body-address">{shippingAddress}</div>
        </div>
      </div>
    </div>
  );
};
