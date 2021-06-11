import React from "react";
import classNames from "classnames";

export const RoundedCorner: React.FC<any> = function (props: any) {
  return (
    <i className={classNames(props.className)}>
      <svg
        width="10"
        height="6"
        viewBox="0 0 10 6"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          d="M9 5L5 1L1 5"
          stroke={props.color || "#8A8A8A"}
          strokeWidth="2"
          strokeLinecap="round"
          strokeLinejoin="round"
        />
      </svg>
    </i>
  );
};
