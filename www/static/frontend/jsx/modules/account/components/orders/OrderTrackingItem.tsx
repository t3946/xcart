import React from "react";
import { getDataToTracking } from "@client/modules/account/utils/get-data-to-tracking";
import useBreakpoint from "@client/modules/account/hooks/useBreakpoint";

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
  const breakpoint = useBreakpoint();
  const trackingViewData = getDataToTracking(
    orderGroupInfo.dc_status,
    breakpoint({ xs: true, md: false })
  );

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
                {breakpoint({
                  md: <div className="order-tracking-line-round" />,
                })}
                <div className="order-tracking-line-round-text">{e.label}</div>
                {breakpoint({
                  xs: <div className="order-tracking-line-round" />,
                  md: null,
                })}
                <div className="order-tracking-line-round-date">{e?.date}</div>
              </div>
            );
          })}
        </div>
      </div>
    </div>
  );
};
