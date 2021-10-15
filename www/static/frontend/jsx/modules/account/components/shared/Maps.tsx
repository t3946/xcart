import * as React from "react";
import { MapContainer, TileLayer, Marker, Popup } from "react-leaflet";
import "leaflet/dist/leaflet.css";
import L from "leaflet";
import { useState } from "react";

const iconPerson = new L.Icon({
  iconUrl: require("leaflet/dist/images/marker-icon.png").default,
  iconRetinaUrl: require("leaflet/dist/images/marker-icon.png").default,
  iconAnchor: null,
  popupAnchor: null,
  shadowSize: null,
  shadowAnchor: null,
  iconSize: new L.Point(25, 40),
});

export const Maps = (props) => {
  console.log(require("leaflet/dist/images/marker-icon-2x.png"));
  console.log(L.Icon);
  const [center, setCenter] = useState([51.505, -0.09]);
  return (
    <>
      <MapContainer center={center} zoom={13} style={{ height: "374px" }}>
        <TileLayer
          attribution='&amp;copy <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
          url="https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}.png"
        />
        <Marker icon={iconPerson} position={[51.505, -0.09]} />
      </MapContainer>
      <button onClick={() => setCenter([100.505, -0.09])}>Set 1</button>
    </>
  );
};
