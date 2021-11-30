import React from "react";

const CallInHours = () => {
  const appData = {
    config: {
      cidev_top_header_code: "Search art supply items, brands and categories",
    },
  };

  return (
    <div className="top-line-call-us d-flex align-items-center">
      <span className="text show-for-medium d-flex align-items-center">
        <img
          src="/static/frontend/dist/images/icons/header/green_check_mark.svg"
          alt={
            "Order online or call us toll free " +
            appData.config.cidev_top_header_code
          }
          className="show-for-medium call-us-green-check me-1"
        />
        Order online or call us toll free
      </span>
      <a
        href={"tel:" + appData.config.cidev_top_header_code}
        className="common-link mx-1"
      >
        {appData.config.cidev_top_header_code}
      </a>
    </div>
  );
};

export default CallInHours;
