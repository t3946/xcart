import React, { useEffect, useState } from "react";
import { Map } from "@client/modules/account/components/shared/Map";
import { OrderTrackingAddressCard } from "@client/modules/account/components/orders/OrderTrackingAddressCard";
import { OrderTrackingItem } from "@client/modules/account/components/orders/OrderTrackingItem";
import { ApiService } from "@client/modules/shared/services/api.service";

interface OrderTrackingGroupProps {
  orderGroupInfo: any;
  orderItem: any;
  shippingPos: [number, number];
}

export const OrderTrackingGroup: React.FC<OrderTrackingGroupProps> = ({
  orderGroupInfo,
  orderItem,
  shippingPos,
}) => {
  const [map, setMap] = useState(null);
  useEffect(() => {
    api
      .get(
        `https://nominatim.openstreetmap.org/search.php?street=${orderGroupInfo.manufacturer.m_address}&city=${orderGroupInfo.manufacturer.m_city}&state=${orderGroupInfo.manufacturer.m_state}&postalcode=${orderGroupInfo.manufacturer.m_zipcode}&polygon_geojson=1&format=jsonv2`
      )
      .then((e) => setMarkersCoordinates([e[0].lat, e[0].lon]))
      .catch((e) => console.log(e));
  }, []);

  const onClickAddressCard = (center: [number, number]) => {
    map?.flyTo(center, 16);
  };

  const api = new ApiService();

  const [markersCoordinates, setMarkersCoordinates] = useState<
    [number, number] | null
  >(null);

  const showTracking = () => {
    if (orderGroupInfo.trackings.length) {
      return orderGroupInfo.trackings.map((e) => {
        return (
          <OrderTrackingItem
            orderGroupInfo={orderGroupInfo}
            trackingInfo={e}
            orderInfo={orderItem}
          />
        );
      });
    }
    return (
      <OrderTrackingItem
        orderGroupInfo={orderGroupInfo}
        orderInfo={orderItem}
      />
    );
  };
  return (
    <React.Fragment>
      {showTracking()}
      <div className="order-tracking-info">
        <div className={"order-tracking-map"}>
          {shippingPos && (
            <Map setMap={setMap} markers={[shippingPos, markersCoordinates]} />
          )}
        </div>
        <div className="order-tracking-info-addresses-cards">
          <OrderTrackingAddressCard
            logo={
              "/static/frontend/images/icons/account/shipping-from-icon.svg"
            }
            title="Shipping from"
            text={`${orderGroupInfo.manufacturer.m_zipcode} ${orderGroupInfo.manufacturer.m_city} 
            ${orderGroupInfo.manufacturer.m_address}`}
            onClick={() => onClickAddressCard(markersCoordinates)}
          />
          <OrderTrackingAddressCard
            logo={"/static/frontend/images/icons/account/shipping-to-icon.svg"}
            title="Shipping to"
            text={`${orderItem.orderInfo.s_zipcode} ${orderItem.orderInfo.s_city} 
            ${orderItem.orderInfo.s_address}`}
            onClick={() => onClickAddressCard(shippingPos)}
          />
        </div>
      </div>
    </React.Fragment>
  );
};
