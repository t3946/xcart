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
      {!!orderGroupInfo.trackings.length && (
        <div className="order-tracking-line-text">
          <div>
            {/* <div className="order-tracking-text">Estimated delivery date:</div>
            <div className="order-tracking-weight-text">
              Monday, May 3rd, 2021
            </div> */}
          </div>
          <div>
            {orderGroupInfo.trackings.map((track, i) => (
              <div
                key={`${track.id}_${i}`}
                className="order-tracking-weight-text"
              >
                <div className="order-tracking-text">
                  Shipped with {track.carrier.name} {track.carrier.method}
                </div>
                Tracking number:{" "}
                <span className="order-tracking-number">{track.tracknum}</span>
              </div>
            ))}
          </div>
        </div>
      )}
      <OrderTrackingLine statuses={orderGroupInfo.statuses_history} />
    </div>
  );
};
