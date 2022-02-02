import React from "react";
import cn from "classnames";

import Styles from "@modules/account/components/lists/ListItemMovableArea.module.scss";

interface ListItemMovableAreaProps {
  drag: any;
  index?: number;
  length?: number;
  onUpClick: () => void;
  onDownClick: () => void;
}

export const ListItemMovableArea: React.FC<ListItemMovableAreaProps> = ({
  drag,
  onUpClick,
  onDownClick,
  index,
  length = Number.MAX_VALUE,
}) => {
  return (
    <div
      {...drag}
      className={cn("list-item-movable-area-container", {
        "d-none": length <= 1,
      })}
    >
      <div
        className={cn("list-item-movable-area-text", {
          [Styles.moveButton_hidden]: index === 0,
        })}
        onClick={index !== 0 ? onUpClick : undefined}
      >
        UP
      </div>
      <img src="/static/frontend/images/icons/account/movable-icon.svg" />
      <div
        className={cn("list-item-movable-area-text", {
          [Styles.moveButton_hidden]: index === length - 1,
        })}
        onClick={index === length - 1 ? onDownClick : undefined}
      >
        <span>{index !== length - 1 && "DOWN"}</span>
      </div>
    </div>
  );
};
