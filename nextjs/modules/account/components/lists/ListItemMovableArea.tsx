import React from "react";
import cn from "classnames";
import Styles from "@modules/account/components/lists/ListItemMovableArea.module.scss";

interface ListItemMovableAreaProps {
  drag: any;
  index?: number;
  length?: number;
  onUpClick: () => void;
  onDownClick: () => void;
  isFirst: boolean;
  isLast: boolean;
  classes?: {
    container?: any;
  };
}

export const ListItemMovableArea: React.FC<ListItemMovableAreaProps> = (
  props
) => {
  const { drag, onUpClick, onDownClick, isFirst, isLast, classes = {} } = props;

  return (
    <div
      {...drag}
      className={cn("list-item-movable-area-container", classes.container)}
    >
      <div
        className={cn("list-item-movable-area-text", {
          [Styles.moveButton_hidden]: isFirst,
        })}
        onClick={isFirst ? null : onUpClick}
      >
        UP
      </div>

      <img src="/static/frontend/images/icons/account/movable-icon.svg" />

      <div
        className={cn("list-item-movable-area-text", {
          [Styles.moveButton_hidden]: isLast,
        })}
        onClick={isLast ? null : onDownClick}
      >
        DOWN
      </div>
    </div>
  );
};
