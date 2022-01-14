import React from "react";
import OrderTrackingLine from "@modules/account/components/orders/OrderTrackingLine";

interface OrderTrackingItemProps {
  orderInfo: any;
  trackingInfo?: any;
  orderGroupInfo: any;
}

export const OrderTrackingItem: React.FC<OrderTrackingItemProps> = ({
  orderInfo,
  trackingInfo,
  orderGroupInfo,
}) => {
  return (
    <div className="order-tracking-container">
      <div className="order-tracking-line-text">
        <div>
          <div className="order-tracking-text">Estimated delivery date:</div>
          <div className="order-tracking-weight-text">
            Monday, May 3rd, 2021
          </div>
        </div>
        <div>
          <div className="order-tracking-text">
            Shipped with USPS First-Class Package Service
          </div>
          {trackingInfo && (
            <>
              <div className="order-tracking-weight-text">
                Tracking number:{" "}
                <span className="order-tracking-number">
                  {trackingInfo.tracknum}
                </span>
              </div>
            </>
          )}
        </div>
      </div>
      <OrderTrackingLine dc_status={orderGroupInfo.dc_status} />
    </div>
  );
};
