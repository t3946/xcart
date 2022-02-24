import React from "react";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";

const CallInHours = () => {
  const cidev_top_header_code = useSelectorAccount(
    (e) => e.config.cidev_top_header_code
  );

  return (
    <div className="top-line-call-us d-flex align-items-center">
      <span className="text show-for-medium d-flex align-items-center">
        <img
          src="/static/frontend/dist/images/icons/header/green_check_mark.svg"
          alt={"Order online or call us toll free " + cidev_top_header_code}
          className="show-for-medium call-us-green-check me-1"
        />
        Order online or call us toll free
      </span>
      <a href={"tel:" + cidev_top_header_code} className="common-link mx-1">
        {cidev_top_header_code}
      </a>
    </div>
  );
};

export default CallInHours;
