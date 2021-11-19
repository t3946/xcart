import React, { ReactElement } from "react";
import classnames from "classnames";

interface IProps {
  className?: any;
}

const Arrow: React.FC<IProps> = (
  props: IProps
): ReactElement => {
  return (
    <svg
      width="93"
      height="362"
      viewBox="0 0 93 362"
      fill="none"
      version="1.1"
      xmlns="http://www.w3.org/2000/svg"
      className={classnames(props.className)}
    >
      <path
        d="M 88.871557,3.8148653 6.6146864,181.28615 88.88897,357.34351"
        stroke="currentColor"
        strokeWidth="8"
        strokeLinecap="round"
        strokeLinejoin="round"
        id="path49"
      />
    </svg>
  );
};

export default Arrow;
