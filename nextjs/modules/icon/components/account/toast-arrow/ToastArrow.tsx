import React from "react";
import cn from "classnames";

interface IProps {
  className?: any;
}

const ToastArrow: React.FC<IProps> = (props) => {
  return (
    <svg
      width="14"
      height="10"
      viewBox="0 0 14 10"
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
      className={cn(props.className)}
    >
      <g clipPath="url(#clip0_5694_6)">
        <g filter="url(#filter0_d_5694_6)">
          <path d="M7 7L2 -7.94466e-08L12 0L7 7Z" fill="currentColor" />
        </g>
      </g>
      <defs>
        <filter
          id="filter0_d_5694_6"
          x="-2"
          y="-3"
          width="18"
          height="15"
          filterUnits="userSpaceOnUse"
          colorInterpolationFilters="sRGB"
        >
          <feFlood flood-opacity="0" result="BackgroundImageFix" />
          <feColorMatrix
            in="SourceAlpha"
            type="matrix"
            values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
            result="hardAlpha"
          />
          <feOffset dy="1" />
          <feGaussianBlur stdDeviation="2" />
          <feComposite in2="hardAlpha" operator="out" />
          <feColorMatrix
            type="matrix"
            values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0"
          />
          <feBlend
            mode="normal"
            in2="BackgroundImageFix"
            result="effect1_dropShadow_5694_6"
          />
          <feBlend
            mode="normal"
            in="SourceGraphic"
            in2="effect1_dropShadow_5694_6"
            result="shape"
          />
        </filter>
        <clipPath id="clip0_5694_6">
          <rect width="14" height="10" fill="white" />
        </clipPath>
      </defs>
    </svg>
  );
};

export default ToastArrow;
