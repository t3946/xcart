import React from "react";

export const ListItemMovableArea = ({ drag }) => {
  return (
    <div {...drag} className="list-item-movable-area-container">
      <div className="list-item-movable-area-text">UP</div>
      <img src="/static/frontend/images/icons/account/movable-icon.svg" />
      <div className="list-item-movable-area-text">DOWN</div>
    </div>
  );
};
