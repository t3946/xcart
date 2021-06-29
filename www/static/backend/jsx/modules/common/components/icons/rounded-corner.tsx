import React from "react";
import Icon from "@admin/icons/icon";

export const RoundedCornerIcon: React.FC<any> = function (props: any) {
  return (
    <Icon {...props}>
      <svg
        width="10"
        height="10"
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
    </Icon>
  );
};
