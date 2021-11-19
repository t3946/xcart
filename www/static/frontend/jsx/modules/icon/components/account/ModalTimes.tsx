import React, { ReactElement } from "react";
import classnames from "classnames";

interface IProps {
  className?: any;
}

const ModalTimes: React.FC<IProps> = (
  props: IProps
): ReactElement => {
  return (
    <svg
      xmlns="http://www.w3.org/2000/svg"
      xmlnsXlink="http://www.w3.org/1999/xlink"
      x="0px"
      y="0px"
      viewBox="0 0 34.7 34.8"
      xmlSpace="preserve"
      className={classnames(props.className)}
      enableBackground="new 0 0 34.7 34.8;"
    >
      <path
        d="M33.8,0.9l-33,33"
        stroke={"currentColor"}
        strokeWidth={"1.5"}
        strokeLinecap={"round"}
        strokeLinejoin={"round"}
      />
      <path
        d="M0.8,0.9l33,33"
        stroke={"currentColor"}
        strokeWidth={"1.5"}
        strokeLinecap={"round"}
        strokeLinejoin={"round"}
      />
    </svg>
  );
};

export default ModalTimes;
