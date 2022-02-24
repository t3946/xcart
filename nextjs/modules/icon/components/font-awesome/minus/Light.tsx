import React from "react";
import classnames from "classnames";

const Light = (props: Record<any, any>): any => {
  return (
    <svg
      aria-hidden="true"
      focusable="false"
      data-prefix="fal"
      data-icon="minus"
      role="img"
      xmlns="http://www.w3.org/2000/svg"
      viewBox="0 0 384 512"
      className={classnames(props.className)}
    >
      <path
        fill="currentColor"
        d="M376 232H8c-4.42 0-8 3.58-8 8v32c0 4.42 3.58 8 8 8h368c4.42 0 8-3.58 8-8v-32c0-4.42-3.58-8-8-8z"
      />
    </svg>
  );
};

export default Light;
