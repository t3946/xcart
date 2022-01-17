import * as React from "react";
import { MapContainer, TileLayer, Marker, Polyline } from "react-leaflet";
import "leaflet/dist/leaflet.css";
import L from "leaflet";

import iconFrom from "../../../../public/images/icons/account/shipping-from.png";
import iconTo from "../../../../public/images/icons/account/shipping-to.png";

function createMapIcon(icon: any, height: number, width: number) {
  return new L.Icon({
    iconUrl: icon,
    iconRetinaUrl: icon,
    iconAnchor: null,
    popupAnchor: null,
    shadowSize: null,
    shadowAnchor: null,
    iconSize: [height, width],
  });
}
const formIcon = createMapIcon(iconFrom, 25, 30);

const toIcon = createMapIcon(iconTo, 30, 20);

interface Map {
  markers: Array<[number, number]>;
}
const Map: React.FC<Map> = ({ markers }) => {
  return (
    <MapContainer
      bounds={markers.map((point) => [point[0], point[1]])}
      center={[
        (Number(markers[0][0]) + Number(markers[1][0])) / 2,
        (Number(markers[0][1]) + Number(markers[1][1])) / 2,
      ]}
      zoom={4}
      style={{ height: "374px" }}
    >
      <TileLayer
        attribution='&amp;copy <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
        url="https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}.png"
      />
      {markers[0] &&
        markers.map((position, index) => (
          <Marker icon={!index ? toIcon : formIcon} position={position} />
        ))}
      <Polyline
        pathOptions={{ color: "#5469d4" }}
        positions={markers.map((item) => [item[0], item[1]])}
      />
    </MapContainer>
  );
};
export default Map;
