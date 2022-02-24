import React from "react";
import cn from "classnames";

interface IProps {
  className?: any;
}

const Tile: React.FC<IProps> = (props: IProps): any => {
  return (
    <svg
      xmlns="http://www.w3.org/2000/svg"
      x="0px"
      y="0px"
      viewBox="-31 33.081 35.915 35.919"
      enableBackground={"new -31 33.081 35.915 35.919"}
      className={cn(props.className)}
    >
      <g>
        <rect
          x="-30.996"
          y="33.081"
          fill="currentColor"
          width="15.902"
          height="15.902"
        />
        <rect
          x="-10.813"
          y="33.081"
          fill="currentColor"
          width="15.902"
          height="15.902"
        />
        <rect
          x="-30.996"
          y="53.122"
          fill="currentColor"
          width="15.902"
          height="15.874"
        />
        <rect
          x="-10.813"
          y="53.122"
          fill="currentColor"
          width="15.902"
          height="15.874"
        />
      </g>
    </svg>
  );
};

export default Tile;
