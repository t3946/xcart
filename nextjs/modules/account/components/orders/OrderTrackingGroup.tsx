import React, { useEffect, useRef, useState } from "react";
import { Map } from "@modules/account/components/shared/Map";
import { OrderTrackingAddressCard } from "@modules/account/components/orders/OrderTrackingAddressCard";
import { OrderTrackingItem } from "@modules/account/components/orders/OrderTrackingItem";
import { ApiService } from "@modules/shared/services/api.service";

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
  const ref = useRef<HTMLDivElement>();
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
        <div ref={ref} className={"order-tracking-map"}>
          {shippingPos && (
            <Map
              map={map}
              width={ref.current.offsetWidth}
              setMap={setMap}
              markers={[shippingPos, markersCoordinates]}
            />
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
