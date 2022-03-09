import React from "react";

interface ListItemMovableAreaProps {
  drag: any;
  onUpClick: () => void;
  onDownClick: () => void;
}

export const ListItemMovableArea: React.FC<ListItemMovableAreaProps> = ({
  drag,
  onUpClick,
  onDownClick,
}) => {
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
