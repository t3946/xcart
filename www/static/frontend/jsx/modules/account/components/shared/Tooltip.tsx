import React from "react";

export const Tooltip = ({ target, content }) => {
  return (
    <div className={"tooltip-container"}>
      <div className={"tooltip-target"}>{target}</div>
      <div className={"tooltip-content"}>{content}</div>
    </div>
  );
};
