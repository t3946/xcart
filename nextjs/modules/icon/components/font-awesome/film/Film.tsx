import React from "react";
import classnames from "classnames";

const Film = (props: Record<any, any>): any => {
  return (
    <svg
      aria-hidden="true"
      focusable="false"
      data-prefix="fad"
      data-icon="film"
      role="img"
      xmlns="http://www.w3.org/2000/svg"
      viewBox="0 0 512 512"
      className={classnames(props.className)}
    >
      <g className="fa-group">
        <path
          fill="currentColor"
          d="M356 280H156a12 12 0 0 0-12 12v96a12 12 0 0 0 12 12h200a12 12 0 0 0 12-12v-96a12 12 0 0 0-12-12zm0-168H156a12 12 0 0 0-12 12v96a12 12 0 0 0 12 12h200a12 12 0 0 0 12-12v-96a12 12 0 0 0-12-12z"
          className="fa-secondary"
          opacity={0.4}
        />
        <path
          fill="currentColor"
          d="M488 64h-8v20a12 12 0 0 1-12 12h-40a12 12 0 0 1-12-12V64H96v20a12 12 0 0 1-12 12H44a12 12 0 0 1-12-12V64h-8A23.94 23.94 0 0 0 0 88v336a23.94 23.94 0 0 0 24 24h8v-20a12 12 0 0 1 12-12h40a12 12 0 0 1 12 12v20h320v-20a12 12 0 0 1 12-12h40a12 12 0 0 1 12 12v20h8a23.94 23.94 0 0 0 24-24V88a23.94 23.94 0 0 0-24-24zM96 372a12 12 0 0 1-12 12H44a12 12 0 0 1-12-12v-40a12 12 0 0 1 12-12h40a12 12 0 0 1 12 12zm0-96a12 12 0 0 1-12 12H44a12 12 0 0 1-12-12v-40a12 12 0 0 1 12-12h40a12 12 0 0 1 12 12zm0-96a12 12 0 0 1-12 12H44a12 12 0 0 1-12-12v-40a12 12 0 0 1 12-12h40a12 12 0 0 1 12 12zm272 208a12 12 0 0 1-12 12H156a12 12 0 0 1-12-12v-96a12 12 0 0 1 12-12h200a12 12 0 0 1 12 12zm0-168a12 12 0 0 1-12 12H156a12 12 0 0 1-12-12v-96a12 12 0 0 1 12-12h200a12 12 0 0 1 12 12zm112 152a12 12 0 0 1-12 12h-40a12 12 0 0 1-12-12v-40a12 12 0 0 1 12-12h40a12 12 0 0 1 12 12zm0-96a12 12 0 0 1-12 12h-40a12 12 0 0 1-12-12v-40a12 12 0 0 1 12-12h40a12 12 0 0 1 12 12zm0-96a12 12 0 0 1-12 12h-40a12 12 0 0 1-12-12v-40a12 12 0 0 1 12-12h40a12 12 0 0 1 12 12z"
          className="fa-primary"
        />
      </g>
    </svg>
  );
};

export default Film;
