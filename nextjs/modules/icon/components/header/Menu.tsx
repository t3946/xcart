import React from "react";
import classnames from "classnames";

const Menu = (props: Record<any, any>): any => {
  return (
    <svg
      version="1.1"
      id="Слой_1"
      xmlns="http://www.w3.org/2000/svg"
      xmlns="http://www.w3.org/1999/xlink"
      x="0px"
      y="0px"
      viewBox="0 0 39 29"
      className={classnames(props.className)}
    >
      <g>
        <path
          style={{ fillRule: "evenodd", clipRule: "evenodd", fill: "#0D2431" }}
          fill="currentColor"
          d="M2.107,0.059h34.786c1.096,0,2.019,0.894,2.019,2.019l0,0
		c0,1.096-0.923,1.99-2.019,1.99H2.107c-1.125,0-2.019-0.894-2.019-1.99l0,0C0.088,0.953,0.982,0.059,2.107,0.059L2.107,0.059z
		 M2.107,24.951h34.786c1.096,0,2.019,0.894,2.019,2.019l0,0c0,1.096-0.923,1.99-2.019,1.99H2.107c-1.125,0-2.019-0.894-2.019-1.99
		l0,0C0.088,25.845,0.982,24.951,2.107,24.951L2.107,24.951z M2.107,12.923h34.786c1.096,0,2.019,0.923,2.019,2.019l0,0
		c0,1.096-0.923,1.99-2.019,1.99H2.107c-1.125,0-2.019-0.894-2.019-1.99l0,0C0.088,13.846,0.982,12.923,2.107,12.923L2.107,12.923z"
        />
      </g>
    </svg>
  );
};

export default Menu;
