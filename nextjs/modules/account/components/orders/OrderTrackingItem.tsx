import React from "react";
import OrderTrackingLine from "@modules/account/components/orders/OrderTrackingLine";

interface OrderTrackingItemProps {
  orderGroupInfo: any;
}

export const OrderTrackingItem: React.FC<OrderTrackingItemProps> = ({
  orderGroupInfo,
}) => {
  return (
    <div className="order-tracking-container">
      {!!orderGroupInfo.xcart_order_tracking.length && (
        <div className="order-tracking-line-text">
          <div>
            {orderGroupInfo.xcart_order_tracking.map((track, i) => (
              <div
                key={`${track.id}_${i}`}
                className="order-tracking-weight-text"
              >
                <div className="order-tracking-text">
                  Shipped with {track.carrier.carrier}{" "}
                  {track.carrier.link.shipping}
                </div>
                Tracking number:{" "}
                <a
                  href={track.carrier.link}
                  target={"_blank"}
                  className="order-tracking-number"
                >
                  {track.tracknum}
                </a>
              </div>
            ))}
          </div>
        </div>
      )}
      <OrderTrackingLine statuses={orderGroupInfo.statuses_history} />
    </div>
  );
};
