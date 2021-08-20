import React from "react";
import classnames from "classnames";

const Light = (props: Record<any, any>): any => {
  return (
    <svg
      aria-hidden="true"
      focusable="false"
      data-prefix="fal"
      data-icon="plus"
      role="img"
      className={classnames(props.className)}
      xmlns="http://www.w3.org/2000/svg"
      viewBox="0 0 384 512"
    >
      <path
        fill="currentColor"
        d="M376 232H216V72c0-4.42-3.58-8-8-8h-32c-4.42 0-8 3.58-8 8v160H8c-4.42 0-8 3.58-8 8v32c0 4.42 3.58 8 8 8h160v160c0 4.42 3.58 8 8 8h32c4.42 0 8-3.58 8-8V280h160c4.42 0 8-3.58 8-8v-32c0-4.42-3.58-8-8-8z"
      />
    </svg>
  );
};

export default Light;
