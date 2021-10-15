import React, { useState } from "react";
import GoogleMapReact from "google-map-react";
import { OrderTrackingAddressCard } from "@client/modules/account/components/orders/OrderTrackingAddressCard";
import { useParams } from "react-router-dom";
import { OrderPageURLParams } from "@client/modules/account/ts/types/order-page-url-params.type";
import { OrderTrackingItem } from "@client/modules/account/components/orders/OrderTrackingItem";
import { Maps } from "@client/modules/account/components/shared/Maps";

const AnyReactComponent = ({ text }) => <div>{text}</div>;

interface OrderTrackingPageProps {
  orderItem: any;
}

export const OrderTrackingPage: React.FC<OrderTrackingPageProps> = ({
  orderItem,
}) => {
  console.log(orderItem);

  const showTracking = () => {
    if (orderItem.trackings.length) {
      return orderItem.trackings.map((e) => {
        return <OrderTrackingItem trackingInfo={e} orderInfo={orderItem} />;
      });
    }
    return <OrderTrackingItem orderInfo={orderItem} />;
  };

  return (
    <div>
      <div className="page-label">Order tracking</div>
      {showTracking()}
      <div className="order-tracking-info">
        <div className={"order-tracking-map"}>
          <Maps />
          {/*<GoogleMapReact*/}
          {/*  defaultCenter={{ lat: -22.917923, lng: -223.688898 }}*/}
          {/*  defaultZoom={5}*/}
          {/*>*/}
          {/*  <AnyReactComponent*/}
          {/*    lat={59.955413}*/}
          {/*    lng={30.337844}*/}
          {/*    text="My Marker"*/}
          {/*  />*/}
          {/*</GoogleMapReact>*/}
        </div>
        <div className="order-tracking-info-addresses-cards">
          <OrderTrackingAddressCard
            logo={
              "/static/frontend/images/icons/account/shipping-from-icon.svg"
            }
            title="Shipping from"
            text="Wilmington, DE 19801
            USA"
          />
          <OrderTrackingAddressCard
            logo={"/static/frontend/images/icons/account/shipping-to-icon.svg"}
            title="Shipping to"
            text={`${orderItem.orderInfo.s_zipcode} ${orderItem.orderInfo.s_city} 
            ${orderItem.orderInfo.s_address}`}
          />
        </div>
      </div>
      <div className="order-tracking-container order-tracking-footer">
        <p>
          <b>Payment status: </b>
          <span>Paid</span>
        </p>
        <div>
          <b>Payment date: </b>
          <span>August 12th, 2021</span>
        </div>
      </div>
    </div>
  );
};
