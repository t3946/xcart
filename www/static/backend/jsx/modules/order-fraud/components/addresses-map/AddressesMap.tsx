import React from "react";
import {
  MapContainer,
  TileLayer,
  Tooltip,
  Polyline,
  CircleMarker,
  ZoomControl,
} from "react-leaflet";
import { useSelector } from "react-redux";
import { FraudCheckStore } from "@admin/modules/order-fraud/ts/types/redux";
import { groupAddresses } from "@admin/modules/order-fraud/utils/group-addresses";
import Typography from "@mui/material/Typography";

interface MapProps {
  width: number;
}

export const AddressesMap: React.FC<MapProps> = ({ width }) => {
  const addressesLocation = useSelector(
    (state: FraudCheckStore) => state.data.addressesLocation
  );
  const groups = groupAddresses(addressesLocation);
  return (
    <MapContainer
      // bounds={addressesLocation.map((add) => [add.longitude, add.latitude])}
      zoom={4}
      scrollWheelZoom={false}
      center={[39.09027262207749, -95.02568326959347]}
      style={{ height: 389, width, zIndex: 0 }}
      zoomAnimation={true}
      zoomControl={false}
    >
      <TileLayer
        attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
      />
      <ZoomControl position="bottomleft" />
      {groups.map((group) => (
        <CircleMarker center={[group.longitude, group.latitude]} radius={20}>
          <Tooltip direction="top" opacity={1} permanent>
            {group.labels.map((label) => (
              <Typography variant="body2">
                {label}
                <br />
              </Typography>
            ))}
          </Tooltip>
        </CircleMarker>
      ))}
      <Polyline
        pathOptions={{ color: "#5469d4" }}
        positions={groups.map((group) => [group.longitude, group.latitude])}
      />
    </MapContainer>
  );
};
