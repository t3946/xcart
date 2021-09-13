import React from "react";

const CallAfterHours = () => {
  return (
    <div className="top-line-call-us">
      <div className="call-us__wrapper call-us__out-of-hours">
        <div className="call-us__order">
          <img
            src="/static/frontend/dist/images/icons/header/place_order_online.svg"
            alt="Place order online 24/7"
            width="23"
            height="26"
            className="call-after-hours-clock"
          />

          <span className="online-ordering-time d-inline-block ms-1 me-10">
            24/7 online ordering
          </span>

          <a href="#" className="call-us__icon" />
        </div>
      </div>
    </div>
  );
};

export default CallAfterHours;
