import React from "react";
import classnames from "classnames";

interface IProps {
  className?: any;
}

const AccountSidebarTablet: React.FC<IProps> = (props: IProps): any => {
  return (
    <svg
      width="19"
      height="11"
      viewBox="0 0 19 11"
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
      className={classnames(props.className)}
    >
      <path
        d="M17 9L9.5 2L2 9"
        stroke="currentColor"
        strokeWidth="3"
        strokeLinecap="round"
        strokeLinejoin="round"
      />
    </svg>
  );
};

export default AccountSidebarTablet;
