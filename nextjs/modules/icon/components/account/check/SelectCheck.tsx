import React from "react";
import cn from "classnames";

const SelectCheck = (props: Record<any, any>): any => {
  const { width = "17", height = "13" } = props;
  return (
    <svg
      width={width}
      height={height}
      viewBox="0 0 17 13"
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
      className={cn(props.className)}
    >
      <path
        d="M1 7L5.5 11.5L16 1"
        stroke="#0F75B0"
        strokeWidth="2"
        strokeLinecap="round"
      />
    </svg>
  );
};

export default SelectCheck;
