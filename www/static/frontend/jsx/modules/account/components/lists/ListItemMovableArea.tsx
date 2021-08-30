import React from "react";

export const ListItemMovableArea = ({ drag, onUpClick, onDownClick }) => {
  return (
    <div {...drag} className="list-item-movable-area-container">
      <div className="list-item-movable-area-text" onClick={onUpClick}>
        UP
      </div>
      <img src="/static/frontend/images/icons/account/movable-icon.svg" />
      <div className="list-item-movable-area-text" onClick={onDownClick}>
        DOWN
      </div>
    </div>
  );
};
