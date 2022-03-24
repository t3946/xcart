import React from "react";
import {useSelector} from "react-redux";
import StoreInterface from "@client/modules/account/ts/types/store.type";

const CallInHours = () => {
  const config = useSelector((e: StoreInterface) => e.config);

  return (
    <div className="top-line-call-us d-flex align-items-center">
      <span className="text show-for-medium d-flex align-items-center">
        <img
          src="/static/frontend/dist/images/icons/header/green_check_mark.svg"
          alt={
            "Order online or call us toll free " +
            config.cidev_top_header_code
          }
          className="show-for-medium call-us-green-check me-1"
        />
        Order online or call us toll free
      </span>
      <a
        href={"tel:" + config.cidev_top_header_code}
        className="common-link mx-1"
      >
        {config.cidev_top_header_code}
      </a>
    </div>
  );
};

export default CallInHours;
