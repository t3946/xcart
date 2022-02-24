import React from "react";
import Styles from "@components/common/tooltip/Tooltip.module.scss";
import cn from "classnames";
import RBTooltip from "react-bootstrap/Tooltip";
import { OverlayTrigger } from "react-bootstrap";

interface IProps {
  id?: string;
  className?: any;
  children?: any;
  overlay: any;
}

const Tooltip: React.FC<IProps> = function (props) {
  const { className, overlay, children } = props;
  const classes = [Styles.tooltip, className];

  return (
    <OverlayTrigger
      trigger="click"
      placement="top"
      delay={{ show: 250, hide: 1000 }}
      overlay={
        <RBTooltip id="tooltip-details" className={cn(classes)}>
          {overlay}
        </RBTooltip>
      }
    >
      {children}
    </OverlayTrigger>
  );
};

export default Tooltip;
