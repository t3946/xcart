import React, { useEffect, useRef, useState, Fragment } from "react";
import Map from "@modules/account/components/shared/Map";
import { OrderTrackingAddressCard } from "@modules/account/components/orders/OrderTrackingAddressCard";
import { OrderTrackingItem } from "@modules/account/components/orders/OrderTrackingItem";
import { ApiService } from "@modules/shared/services/api.service";
import { OrderGroup } from "@modules/account/ts/types/order/orders-store.types";
import { OrderView } from "@modules/account/ts/types/order/order-view.types";
import dynamic from "next/dynamic";
import cn from "classnames";

import Styles from "@modules/account/components/orders/OrderTrackingGroup.module.scss";
import AddressText from "@components/common/address-text/AddressText";

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
      .then((e) => {
        if (e.length) {
          setMarkerCarrier([e[0].lat, e[0].lon]);
        }
      })
      .catch((e) => console.log(e));
  }, []);

  const onClickAddressCard = (center: [number, number]) => {
    map?.flyTo(center, 16);
  };

  const api = new ApiService();

  const [markerCarrier, setMarkerCarrier] = useState<[number, number] | null>(
    null
  );

  return (
    <Fragment>
      <OrderTrackingItem orderGroupInfo={orderGroupInfo} />
      <div className="order-tracking-info">
        {shippingPos && !!shippingPos.length && markerCarrier && (
          <div ref={ref} className={"order-tracking-map"}>
            <Map markers={[shippingPos, markerCarrier]} />
          </div>
        )}
        <div
          className={cn(
            Styles.addresses,
            {
              [Styles.addresses_fullWidth]: !(
                shippingPos &&
                !!shippingPos.length &&
                markerCarrier
              ),
            },
            "order-tracking-info-addresses-cards"
          )}
        >
          <OrderTrackingAddressCard
            logo={
              "/static/frontend/images/icons/account/shipping-from-icon.svg"
            }
            title="Shipping from"
            text={
              <AddressText
                address={{
                  city: orderGroupInfo.manufacturer?.city,
                  state: !!orderGroupInfo.manufacturer?.state && {
                    label: orderGroupInfo.manufacturer?.state,
                  },
                  zip: orderGroupInfo.manufacturer?.zip,
                  country: !!orderGroupInfo.manufacturer?.country && {
                    label: orderGroupInfo.manufacturer?.country,
                  },
                }}
              />
            }
            onClick={() => onClickAddressCard(markersCoordinates)}
          />
          <OrderTrackingAddressCard
            logo={"/static/frontend/images/icons/account/shipping-to-icon.svg"}
            title="Shipping to"
            text={
              <AddressText
                address={{
                  street: orderItem.address.shippingAddress,
                  city: orderItem.address.shippingCity,
                  state: !!orderItem.address.shippingState && {
                    label: orderItem.address.shippingState,
                  },
                  zip: orderItem.address.shippingZip,
                }}
              />
            }
            onClick={() => onClickAddressCard(shippingPos)}
          />
        </div>
      </div>
    </Fragment>
  );
};
