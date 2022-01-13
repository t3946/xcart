import React, { ReactElement } from "react";
import classnames from "classnames";

interface IProps {
  className?: any;
}

const NoItems: React.FC<IProps> = (props: IProps): ReactElement => {
  return (
    <svg
      width="26"
      height="21"
      viewBox="0 0 26 21"
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
      className={classnames(props.className)}
    >
      <path
        d="M22.1457 10.6929C22.1457 15.632 18.1418 19.6359 13.2027 19.6359C8.26366 19.6359 4.25977 15.632 4.25977 10.6929C4.25977 5.75389 8.26366 1.75 13.2027 1.75C18.1418 1.75 22.1457 5.75389 22.1457 10.6929Z"
        stroke="currentColor"
        strokeWidth="1.5"
      />
      <path
        d="M3.20077 19.6168L23.2019 1.92335"
        stroke="currentColor"
        strokeWidth="1.5"
        strokeLinecap="round"
        strokeLinejoin="round"
      />
    </svg>
  );
};

export default NoItems;
