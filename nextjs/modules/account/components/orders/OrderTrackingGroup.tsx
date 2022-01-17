import React, { useEffect, useRef, useState, Fragment } from "react";
import Map from "@modules/account/components/shared/Map";
import { OrderTrackingAddressCard } from "@modules/account/components/orders/OrderTrackingAddressCard";
import { OrderTrackingItem } from "@modules/account/components/orders/OrderTrackingItem";
import { ApiService } from "@modules/shared/services/api.service";
import { OrderGroup } from "@modules/account/ts/types/order/orders-store.types";
import { OrderView } from "@modules/account/ts/types/order/order-view.types";
import dynamic from "next/dynamic";
interface OrderTrackingGroupProps {
  orderGroupInfo: OrderGroup;
  orderItem: OrderView;
  shippingPos: [number, number];
}

export const OrderTrackingGroup: React.FC<OrderTrackingGroupProps> = ({
  orderGroupInfo,
  orderItem,
  shippingPos,
}) => {
  const Map = dynamic(() => import("@modules/account/components/shared/Map"), {
    ssr: false,
  });

  const ref = useRef<HTMLDivElement>();
  const [map, setMap] = useState(null);
  useEffect(() => {
    api
      .get(
        `https://nominatim.openstreetmap.org/search.php?street=${orderGroupInfo.manufacturer?.address}&city=${orderGroupInfo.manufacturer?.city}&state=${orderGroupInfo.manufacturer?.state}&postalcode=${orderGroupInfo.manufacturer?.zip}&polygon_geojson=1&format=jsonv2`
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
    return orderGroupInfo.tracks?.map((track) => {
      return (
        <OrderTrackingItem
          orderGroupInfo={orderGroupInfo}
          trackingInfo={track}
          orderInfo={orderItem}
        />
      );
    });
    // return (
    //   <OrderTrackingItem
    //     orderGroupInfo={orderGroupInfo}
    //     orderInfo={orderItem}
    //   />
    // );
  };
  return (
    <Fragment>
      {showTracking()}
      <div className="order-tracking-info">
        <div ref={ref} className={"order-tracking-map"}>
          {shippingPos && <Map markers={[shippingPos, markersCoordinates]} />}
        </div>
        <div className="order-tracking-info-addresses-cards">
          <OrderTrackingAddressCard
            logo={
              "/static/frontend/images/icons/account/shipping-from-icon.svg"
            }
            title="Shipping from"
            text={`${orderGroupInfo.manufacturer?.zip} ${orderGroupInfo.manufacturer?.city} 
            ${orderGroupInfo.manufacturer?.address}`}
            onClick={() => onClickAddressCard(markersCoordinates)}
          />
          <OrderTrackingAddressCard
            logo={"/static/frontend/images/icons/account/shipping-to-icon.svg"}
            title="Shipping to"
            text={`${orderItem.address.shippingZip} ${orderItem.address.shippingCity} 
            ${orderItem.address.shippingAddress}`}
            onClick={() => onClickAddressCard(shippingPos)}
          />
        </div>
      </div>
    </Fragment>
  );
};
