import React from "react";
import { getDataToTracking } from "@client/modules/account/utils/get-data-to-tracking";
import { transportationTypes } from "@client/modules/account/ts/consts/transportations-types";

interface OrderTrackingItemProps {
  orderInfo: any;
  trackingInfo?: any;
}

export const OrderTrackingItem: React.FC<OrderTrackingItemProps> = ({
  orderInfo,
  trackingInfo,
}) => {
  const trackingViewData = getDataToTracking(transportationTypes[2]);

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
          <div className="order-tracking-weight-text">
            Tracking number:{" "}
            <span className="order-tracking-number">
              9361289733009135532388
            </span>
          </div>
        </div>
      </div>
      <div className={"order-tracking-line"}>
        <div
          style={trackingViewData.lineWidth}
          className="order-tracking-line-blue"
        />
        <div className="order-tracking-rounds">
          {trackingViewData.items.map((e, index) => {
            return (
              <div
                className={`order-tracking-line-round-container ${e.containerClass}`}
                style={e.roundStyle}
                key={index}
              >
                <div className="order-tracking-line-round" />
                <div className="order-tracking-line-round-text">Text</div>
                {e.date && (
                  <div className="order-tracking-line-round-date">{e.date}</div>
                )}
              </div>
            );
          })}
        </div>
      </div>
    </div>
  );
};
