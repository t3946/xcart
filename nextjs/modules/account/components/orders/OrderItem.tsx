import React, { useState } from "react";
import Link from "next/link";
import { useAccordion } from "@modules/account/hooks/useAccordion";
import { OrderStoreItem } from "@modules/account/ts/types/order/orders-store.types";
import Button, { ETheme } from "@modules/ui/forms/Button";
import Price from "@components/common/price/Price";

interface OrderItemProps {
  order: OrderStoreItem;
  orderType: string;
  orderItem?: any;
}

export const OrderItem: React.FC<OrderItemProps> = ({ order, orderType }) => {
  const orderDate = new Date(Number(order.date * 1000)).toLocaleDateString(
    "en-EN",
    {
      month: "long",
      day: "2-digit",
      year: "numeric",
    }
  );
  const accordion = useAccordion(200);
  const [showAllItems, setShowAllItems] = useState(false);

  let totalProducts = 0;
  for (const group of order.groups) {
    totalProducts += (group.products && group.products?.length) || 0;
  }

  return (
    <div className="order-item-container">
      <div className="order-item-header-container">
        <div className="order-item-body-left-side header-left">
          <div className="row">
            <div className="col">
              <div className="order-item-name ws-nowrap text-center mb-3">
                Order # <b>{order.orderNumber}</b>
              </div>
            </div>

            <div className="col">
              <Link
                href={`/order/[id]/order-tracking`}
                as={`/order/${order.orderId}/order-tracking`}
              >
                <a className={"text-decoration-none w-100 w-md-auto"}>
                  <Button className={"w-100 w-md-auto"} theme={ETheme.outlined}>
                    order details
                  </Button>
                </a>
              </Link>
            </div>
          </div>
        </div>
        <div className="order-item-body-right-side header-right">
          <div>
            <div>ORDER DATE</div>
            <div>{orderDate}</div>
          </div>
          <div>
            <div className="order-item-header-grand-total">GRAND TOTAL</div>
            <div className="order-item-header-grand-total">
              <b>
                <Price price={order.total} />
              </b>
            </div>
          </div>
        </div>
      </div>
      <div className="order-item-body-container">
        <div className="order-item-body-left-side">
          <div className="order-item-body-right-side"></div>
          <div className="order-item-body-title">items ordered</div>

          {order.groups[0]?.details[0] && (
            <div className={"order-item-body-product-container"}>
              <div className="order-item-body-product-left-part">
                <img
                  className="order-item-body-product-img"
                  src={order.groups[0].details[0].image}
                />
                <div>
                  <a
                    href={order.groups[0].details[0].url}
                    className="order-item-body-product-name"
                  >
                    {order.groups[0].details[0].product}
                  </a>
                  <div className="order-item-body-product-sku">
                    {order.groups[0].details[0].code}
                  </div>
                </div>
              </div>
              <div className="order-item-body-product-right-part-text">
                x {order.groups[0].details[0].amount}
              </div>
            </div>
          )}

          <div
            style={{
              height: accordion.height,
            }}
            ref={accordion.ref}
            className="order-items-list"
          >
            {order.groups.map((group, groupIndex) => {
              return group.details.map((product, itemIndex) => {
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
                        <a
                          href={product.url}
                          className="order-item-body-product-name"
                        >
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

          {totalProducts > 1 && (
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
          <div className="order-item-body-address">
            {order.address.shippingAddress ?? ""}
            <br />
            {order.address.shippingCity ?? ""}{" "}
            {order.address.shippingState
              ? `${order.address.shippingState},`
              : ""}{" "}
            {order.address.shippingZip ?? ""}
          </div>
        </div>
      </div>
    </div>
  );
};
