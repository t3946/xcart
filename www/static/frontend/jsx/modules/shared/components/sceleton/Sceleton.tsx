import React from "react";

interface SceletonPropsDto {
  height: number;
  maxWidth: number | string;
  width?: number;
  margin?: string;
}

export const Sceleton: React.FC<SceletonPropsDto> = ({
  height,
  maxWidth,
  width,
  margin,
}) => {
  return (
    <div
      className="sceleton"
      style={{
        height,
        maxWidth,
        margin: margin,
        width: width || "100%",
        padding: margin,
      }}
    />
  );
};
