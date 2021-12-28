import React from "react";
import cn from "classnames";

interface IProps {
  className?: any;
}

const Switch: React.FC<IProps> = (props: IProps) => {
  return (
    <svg
      version="1.1"
      id="Слой_1"
      xmlns="http://www.w3.org/2000/svg"
      x="0px"
      y="0px"
      viewBox="0 0 17.667 17.667"
      className={cn(props.className)}
    >
      <g>
        <path
          style={{ fillRule: "evenodd", clipRule: "evenodd", fill: "#D2D2D2" }}
          d="M1.956,0L1.956,0h8.787c1.077,0,1.956,0.879,1.956,1.956l0,0c0,1.106-0.879,1.984-1.956,1.984h-3.6
		l9.865,9.836c0.879,0.907,0.879,2.353,0,3.231l0,0c-0.879,0.879-2.324,0.879-3.231,0L3.94,7.143v3.6
		c0,1.077-0.879,1.956-1.984,1.956l0,0C0.879,12.699,0,11.82,0,10.743V1.956C0,0.879,0.879,0,1.956,0L1.956,0z"
        />
      </g>
    </svg>
  );
};

export default Switch;
