import React from "react";
import cn from "classnames";
import OrderTrackingLine from "@modules/account/components/orders/OrderTrackingLine";
import RectangularButton from "@modules/account/components/common/RectangularButton";

import DashboardStyles from "@modules/account/components/dashboard/Dashboard.module.scss";
import Styles from "@modules/account/components/dashboard/OrderTracking.module.scss";

interface IProps {
  orderInfo: any;
  trackingInfo?: any;
  orderGroupInfo: any;
}

const OrderTracking: React.FC<IProps> = ({
  orderInfo,
  trackingInfo,
  orderGroupInfo,
}) => {
  return (
    <RectangularButton
      classNames={{ container: [Styles.container, DashboardStyles.card] }}
      header={
        <div className={cn(Styles.header, "d-flex w-100 align-items-end")}>
          <div className={Styles.order}>
            <div className={Styles.orderNumber}>Order # {orderInfo.number}</div>
          </div>
          <div className="d-none d-md-block text-center text-lg-start flex-grow-1">
            <span className={Styles.textBlue}>View details</span>
          </div>
          <div className="d-none d-md-inline">
            <span className={Styles.textBlue}>Invoice.pdf</span>
          </div>
        </div>
      }
      body={
        <div className="w-100">
          <div
            className={cn(
              Styles.orderTrack,
              "mt-14",
              "mt-md-10",
              "mb-18",
              "mt-lg-14"
            )}
          >
            <b>
              Tracking number <br className="d-md-none" />
              <span className={Styles.textBlue}>{trackingInfo.tracknum}</span>
            </b>
            <span
              className={cn(Styles.textBlue, "d-block float-right d-md-none")}
            >
              Invoice
            </span>
          </div>
          <OrderTrackingLine dc_status={orderGroupInfo.dc_status} />
        </div>
      }
      footer={
        <button
          className={cn(
            Styles.button,
            "mt-4",
            "d-md-none",
            "form-button",
            "form-button__outline",
            "fw-bold"
          )}
        >
          view details
        </button>
      }
    />
  );
};

export default OrderTracking;
