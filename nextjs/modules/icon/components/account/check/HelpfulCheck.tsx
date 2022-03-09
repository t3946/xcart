import React from "react";
import classnames from "classnames";

const AlertCheck = (props: Record<any, any>): any => {
  return (
    <svg
      width="16"
      height="17"
      viewBox="0 0 16 17"
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
      className={classnames(props.className)}
    >
      <path
        fillRule="evenodd"
        clipRule="evenodd"
        d="M0.887789 13.747C2.53698 15.9716 3.89829 17.4925 5.15727 15.5977C7.54255 12.4423 11.5009 4.23177 14.9818 1.69641C15.4969 1.14555 15.3192 0.248468 14.5288 0.0264157C11.6364 -0.684085 4.59822 13.1743 3.8137 13.1364C3.32968 13.134 2.72492 11.8689 2.01988 11.1553C0.921216 9.96489 -1.20824 11.0997 0.887789 13.747Z"
        fill="currentColor"
      />
    </svg>
  );
};

export default AlertCheck;
