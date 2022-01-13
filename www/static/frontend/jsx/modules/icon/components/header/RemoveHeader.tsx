import React from "react";
import cn from "classnames";

interface IProps {
  className?: any;
}

const RemoveHeader: React.FC<IProps> = (props) => {
  return (
    <svg
      version="1.1"
      xmlns="http://www.w3.org/2000/svg"
      xlink="http://www.w3.org/1999/xlink"
      x="0px"
      y="0px"
      viewBox="0 0 33.874 33.874"
      xml="preserve"
      className={cn(props.className)}
    >
      <g>
        <path
          style={{ fillRule: "evenodd", clipRule: "evenodd", fill: "#C6C6C6" }}
          d="M16.951,33.874c9.326,0,16.923-7.597,16.923-16.923
		C33.874,7.597,26.277,0,16.951,0C7.597,0,0,7.597,0,16.951C0,26.277,7.597,33.874,16.951,33.874L16.951,33.874z"
        />
        <path
          style={{ fillRule: "evenodd", clipRule: "evenodd", fill: "#FFFFFF" }}
          d="M8.362,8.362L8.362,8.362c-0.935,0.935-0.935,2.466,0,3.402
		l5.187,5.187L8.362,22.11c-0.935,0.935-0.935,2.466,0,3.402l0,0c0.935,0.935,2.466,0.935,3.402,0l5.187-5.187l5.159,5.187
		c0.935,0.935,2.466,0.935,3.402,0l0,0c0.935-0.935,0.935-2.466,0-3.402l-5.187-5.159l5.187-5.187c0.935-0.935,0.935-2.466,0-3.402
		l0,0c-0.935-0.935-2.466-0.935-3.402,0l-5.159,5.187l-5.187-5.187C10.828,7.427,9.298,7.427,8.362,8.362L8.362,8.362z"
        />
      </g>
    </svg>
  );
};

export default RemoveHeader;
