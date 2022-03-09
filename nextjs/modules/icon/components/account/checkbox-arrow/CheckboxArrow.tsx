import React from "react";
import cn from "classnames";

interface IProps {
  className?: any;
}

const CheckboxArrow: React.FC<IProps> = ({ className }) => {
  return (
    <svg
      width="15"
      height="14"
      viewBox="0 0 15 14"
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
      className={cn(className)}
    >
      <path
        fillRule="evenodd"
        clipRule="evenodd"
        d="M14.578 0.184057C15.0287 0.503287 15.1353 1.12742 14.816 1.5781L6.31605 13.5781C6.13717 13.8306 5.85147 13.9861 5.54229 13.9992C5.23311 14.0123 4.93529 13.8815 4.73573 13.645L0.23573 8.31162C-0.120422 7.88951 -0.0669556 7.25861 0.355151 6.90246C0.777257 6.5463 1.40816 6.59977 1.76431 7.02188L5.43096 11.3675L13.184 0.422064C13.5032 -0.0286142 14.1274 -0.135174 14.578 0.184057Z"
        fill="#4A4949"
      />
    </svg>
  );
};

export default CheckboxArrow;
