import React from "react";
import { RoundedCornerIcon } from "@admin/icons/icons";
import { RoundedCornerDoubleIcon } from "@admin/icons/icons";
import appData from "@admin/utils/app-data";

const HatReference: React.FC<any> = function () {
  const logo = appData().hat.logoUrl;

  function timeTemplate(): any {
    const timeList = [];

    for (const time of appData().hat.time) {
      timeList.push(
        <li className="time-item time-list_item">
          <span className="time-number">{time.time}</span>
          <span className="time-cation">{time.caption}</span>
        </li>
      );
    }

    return <ul className="m-0 time-list list-unstyled">{timeList}</ul>;
  }

  return (
    <div className="pt-4 pb-4">
      <div className="row">
        <div className="col column__left">
          <div className="d-flex align-items-center">
            <div className="flex-grow-1">
              <img src={logo} alt="logo" />
            </div>
            <RoundedCornerIcon className="ml-2.5" color="#000000" />
          </div>
        </div>
        <div className="col">
          <div className="column-right-wrapper">
            <div className="holiday-block hat_holiday-block">
              <div className="hat-date">{appData().hat.date}</div>
              <div className="until-holiday">{appData().hat.holiday}</div>
            </div>

            <div className="time-block">{timeTemplate()}</div>
          </div>
        </div>
        <div className="col column__right align-items-end d-flex flex-column">
          <button className="logout-button button">
            Log out
            <RoundedCornerDoubleIcon className="logout-button_icon" />
          </button>
          <div className="logged-as mt-2">
            {appData().hat.user} is logged in!
          </div>
        </div>
      </div>
    </div>
  );
};

export default HatReference;
