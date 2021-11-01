import * as React from "react";
import { MapContainer, TileLayer, Marker, Popup } from "react-leaflet";
import "leaflet/dist/leaflet.css";
import L from "leaflet";
import { Dispatch, useEffect, useRef, useState } from "react";
import { useSelector } from "react-redux";
import { AccountStore } from "@client/modules/account/ts/types/store.type";

const fromIconFile = require("../../../../../images/icons/account/shipping-from.png");
const toIconFile = require("../../../../../images/icons/account/shipping-to.png");

function createMapIcon(icon: string, height: number, width: number) {
  return new L.Icon({
    iconUrl: icon.default,
    iconRetinaUrl: icon.default,
    iconAnchor: null,
    popupAnchor: null,
    shadowSize: null,
    shadowAnchor: null,
    iconSize: new L.Point(width, height),
  });
}

function getDistance(from: [number, number], to: [number, number]) {
  const [lat1, lon1] = from;
  const [lat2, lon2] = to;

  const R = 6371e3;
  const φ1 = (lat1 * Math.PI) / 180;
  const φ2 = (lat2 * Math.PI) / 180;
  const Δφ = ((lat2 - lat1) * Math.PI) / 180;
  const Δλ = ((lon2 - lon1) * Math.PI) / 180;

  const a =
    Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
    Math.cos(φ1) * Math.cos(φ2) * Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
  const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

  return R * c;
}

function calculateZoomLevel(screenWidth: number, a: number) {
  const widthInPixels = screenWidth;
  const equatorLength = 40075004;
  let metersPerPixel = equatorLength / 256;
  let zoomLevel = 1;
  while (metersPerPixel * widthInPixels >= a) {
    metersPerPixel /= 4;
    ++zoomLevel;
  }
  return zoomLevel;
}

const formIcon = createMapIcon(fromIconFile, 25, 30);

const toIcon = createMapIcon(toIconFile, 30, 20);

interface MapProps {
  markers: Array<[number, number]>;
  setMap: Dispatch<any>;
  width: number;
  map: any;
}

export const Map: React.FC<MapProps> = ({ markers, setMap, width, map }) => {
  const breakpoint = useSelector((e: AccountStore) => e.main.breakpoint);

  useEffect(() => {
    if (map) {
      map.setZoom(
        calculateZoomLevel(
          width,
          Math.round(getDistance(markers[0], markers[1]))
        )
      );
    }
  }, [breakpoint, map]);

  return (
    <div>
      <MapContainer
        whenCreated={setMap}
        center={[
          (Number(markers[0][0]) + Number(markers[1][0])) / 2,
          (Number(markers[0][1]) + Number(markers[1][1])) / 2,
        ]}
        zoom={1}
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
      </MapContainer>
    </div>
  );
};
