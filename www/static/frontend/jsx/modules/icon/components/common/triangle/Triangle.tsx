import React from "react";
import cn from "classnames";

interface IProps {
  className?: any;
}

const Triangle: React.FC<IProps> = (props: IProps): any => {
  return (
    <svg
      width="8"
      height="6"
      viewBox="0 0 8 6"
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
      className={cn(props.className)}
    >
      <path d="M8 0H0L4 6L8 0Z" fill="currentColor" />
    </svg>
  );
};

export default Triangle;
