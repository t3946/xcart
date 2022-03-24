import React from "react";
import CallAfterHours from "./CallAfterHours";
import CallInHours from "./CallInHours";
import useSelectorAccount from "@client/modules/account/hooks/useSelectorAccount";
// import useSelectorA from "react-redux";

const TopLine = () => {
  const site = useSelectorAccount((e) => e.site);

  //информация о том в какие часы лучше звонить
  function callHoursTemplate() {
    if (site.workingDayTimeNow) {
      return <CallAfterHours />;
    } else {
      return <CallInHours />;
    }
  }

  return (
    <div className="top-header show-for-large">
      <div className="container">
        <div className="row">
          <div className="col-sm-4 d-flex">
            <span className="top-line-site-name">{site.shortName}</span>
          </div>

          <div className="col-sm-8">
            <div className="align-items-start call_lang d-flex justify-content-end">
              {callHoursTemplate()}

              <a href="#" className="top-line-lang-icon top-line_lang-icon"></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default TopLine;
