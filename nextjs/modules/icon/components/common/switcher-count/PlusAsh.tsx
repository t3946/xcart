import React from "react";

interface IProps {
  className?: any;
  width?: string;
  height?: string;
}

const PlusAsh: React.FC<IProps> = ({ className, width, height }) => {
  return (
    <svg
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
      viewBox="0 0 13 13"
      width={width}
      height={height}
      className={className}
    >
      <g>
        <path fill="#49474a" d="M5.571 0h1.857v13H5.571z" />
        <path fill="#49474a" d="M0 7.429V5.572h13v1.857z" />
      </g>
    </svg>
  );
};

export default PlusAsh;
