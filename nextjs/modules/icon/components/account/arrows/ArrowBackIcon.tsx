import React, { ReactElement } from "react";
import cn from "classnames";

interface IProps {
  className?: any;
}

const ArrowBackIcon: React.FC<IProps> = (props: IProps): ReactElement => {
  return (
    <svg
      width="15"
      height="13"
      viewBox="0 0 15 13"
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
      className={cn(props.className)}
    >
      <path
        d="M13 6.72917L2 6.72917M2 6.72917L7.06 2M2 6.72917L7.06 11"
        stroke="currentColor"
        strokeWidth="3"
        strokeLinecap="round"
        strokeLinejoin="round"
      />
    </svg>
  );
};

export default ArrowBackIcon;
