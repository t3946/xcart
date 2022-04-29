import React from "react";
import cn from "classnames";

interface IProps {
  className?: any;
}

const Chevron: React.FC<IProps> = (props: IProps): any => {
  return (
    <svg width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg" className={cn(props.className)}>
      <path d="M11 1L6 6L1 1" stroke="#393939" strokeWidth="2"/>
    </svg>
  );
};

export default Chevron;
