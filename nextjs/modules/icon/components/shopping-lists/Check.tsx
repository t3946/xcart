import React, { ReactElement } from "react";
import cn from "classnames";

interface IProps {
  className?: any;
}

const Times: React.FC<IProps> = (props: IProps): ReactElement => {
  return (
    <svg
      width="17"
      height="13"
      viewBox="0 0 17 13"
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
      className={cn(props.className)}
    >
      <path
        d="M1 7L5.5 11.5L16 1"
        stroke="currentColor"
        strokeWidth="2"
        strokeLinecap="round"
      />
    </svg>
  );
};

export default Times;
