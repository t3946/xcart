import React from "react";

const CallInHours = () => {
  return (
    <div className="top-line-call-us">
      <span className="text show-for-medium">
        <img
          src="/static/frontend/dist/images/icons/header/green_check_mark.svg"
          alt={
            "Order online or call us toll free " +
            appData.config.cidev_top_header_code
          }
          className="show-for-medium call-us-green-check mr-1"
        />
        Order online or call us toll free
      </span>
      <a
        href={"tel:" + appData.config.cidev_top_header_code}
        className="common-link ml-1 mr-1"
      >
        {appData.config.cidev_top_header_code}
      </a>
    </div>
  );
};

export default CallInHours;
