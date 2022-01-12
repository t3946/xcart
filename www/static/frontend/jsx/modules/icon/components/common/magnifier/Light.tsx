import React from "react";
import cn from "classnames";

interface IProps {
  className?: any;
}

const Magnifier: React.FC<IProps> = (props: IProps): any => {
  return (
    <svg
      version="1.1"
      xmlns="http://www.w3.org/2000/svg"
      xmlnsXlink="http://www.w3.org/1999/xlink"
      x="0px"
      y="0px"
      viewBox="0 0 24 24"
      xmlSpace="preserve"
      className={cn(props.className)}
    >
      <g>
        <path
          fillRule={"evenodd"}
          clipRule={"evenodd"}
          fill={"currentColor"}
          d="M20,17.3l3.5,3.5c0.7,0.7,0.7,1.8,0,2.5l0,0c-0.7,0.7-1.8,0.7-2.5,0l-3.4-3.4c-2,1.4-4.3,2.2-6.6,2.2 c-2.8,0-5.6-1.1-7.8-3.2c-2.1-2.2-3.2-5-3.2-7.8c0-2.8,1.1-5.6,3.2-7.8c2.2-2.1,5-3.2,7.8-3.2s5.6,1.1,7.8,3.2 c2.1,2.2,3.2,5,3.2,7.8C21.9,13.3,21.3,15.5,20,17.3L20,17.3z M11,3.1c-2,0-4.1,0.8-5.7,2.4S3,9.1,3,11.1c0,2,0.8,4.1,2.4,5.7 c1.6,1.6,3.6,2.4,5.7,2.4s4.1-0.8,5.7-2.4c1.6-1.6,2.4-3.6,2.4-5.7c0-2-0.8-4.1-2.4-5.7S13,3.1,11,3.1L11,3.1z"
        />
      </g>
    </svg>
  );
};

export default Magnifier;
