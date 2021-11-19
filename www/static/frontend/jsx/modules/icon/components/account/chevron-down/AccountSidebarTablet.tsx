import React from "react";
import classnames from "classnames";

interface IProps {
  className?: any;
}

const AccountSidebarTablet: React.FC<IProps> = (
  props: IProps
): any => {
  return (
    <svg
      width="22"
      height="12"
      viewBox="0 0 22 12"
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
      className={classnames(props.className)}
    >
      <path
        d="M1 1L11 11L21 1"
        stroke="currentColor"
        strokeWidth="2"
        strokeLinecap="round"
        strokeLinejoin="round"
      />
    </svg>
  );
};

export default AccountSidebarTablet;
