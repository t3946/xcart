import React, { useState } from "react";
import Link from "next/link";
import { useAccordion } from "@modules/account/hooks/useAccordion";
import { OrderStoreItem } from "@modules/account/ts/types/order/orders-store.types";

interface OrderItemProps {
  order: OrderStoreItem;
  orderType: string;
  orderItem?: any;
}

export const OrderItem: React.FC<OrderItemProps> = ({ order, orderType }) => {
  const orderDate = new Date(Number(order.date)).toLocaleDateString("en-EN", {
    month: "long",
    day: "2-digit",
    year: "numeric",
  });

  const accordion = useAccordion(200);

  const [showAllItems, setShowAllItems] = useState(false);

  const shippingAddress = `${order.address.shippingZip} ${order.address.shippingCity} ${order.address.shippingAddress}`;

  return (
    <div className="order-item-container">
      <div className="order-item-header-container">
        <div className="order-item-body-left-side header-left">
          <div className="order-item-name">
            Order # <b>{order.orderNumber}</b>
          </div>
          <Link
            href={`/order/[id]/order-tracking`}
            as={`/order/${order.orderId}/order-tracking`}
          >
            <a>
              <button className="form-button form-button__outline order-details-btn">
                order details
              </button>
            </a>
          </Link>
        </div>
        <div className="order-item-body-right-side header-right">
          <div>
            <div>ORDER DATE</div>
            <div>{orderDate}</div>
          </div>
          <div>
            <div className="order-item-header-grand-total">GRAND TOTAL</div>
            <div className="order-item-header-grand-total">
              <b>US$ {order.total}</b>
            </div>
          </div>
        </div>
      </div>
      <div className="order-item-body-container">
        <div className="order-item-body-left-side">
          <div className="order-item-body-right-side"></div>
          <div className="order-item-body-title">items ordered</div>

          <div className={"order-item-body-product-container"}>
            <div className="order-item-body-product-left-part">
              <img
                className="order-item-body-product-img"
                src={order.groups[0].products[0].image}
              />
              <div>
                <a className="order-item-body-product-name">
                  {order.groups[0].products[0].product}
                </a>
                <div className="order-item-body-product-sku">
                  {order.groups[0].products[0].code}
                </div>
              </div>
            </div>
            <div className="order-item-body-product-right-part-text">
              x {order.groups[0].products[0].amount}
            </div>
          </div>

          <div
            style={{
              height: accordion.height,
            }}
            ref={accordion.ref}
            className="order-items-list"
          >
            {order.groups.map((group, groupIndex) => {
              return group.products.map((product, itemIndex) => {
                if (groupIndex === 0 && itemIndex === 0) {
                  return null;
                }

                return (
                  <div
                    className={"order-item-body-product-container"}
                    key={`${groupIndex}-${itemIndex}`}
                  >
                    <div className="order-item-body-product-left-part">
                      <img
                        className="order-item-body-product-img"
                        src={product.image}
                      />
                      <div>
                        <a className="order-item-body-product-name">
                          {product.product}
                        </a>
                        <div className="order-item-body-product-sku">
                          {product.code}
                        </div>
                      </div>
                    </div>
                    <div className="order-item-body-product-right-part-text">
                      x {product.amount}
                    </div>
                  </div>
                );
              });
            })}
          </div>

          {order.groups.length > 1 && (
            <button
              onClick={() => {
                accordion.onItemClick();
                setShowAllItems(!showAllItems);
              }}
              className="form-button form-button__outline order-item-show-btn"
            >
              {!showAllItems ? "show more" : "hide"}
            </button>
          )}
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
