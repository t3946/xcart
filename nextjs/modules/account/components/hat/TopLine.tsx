import React from "react";
import CallAfterHours from "@modules/account/components/hat/CallAfterHours";
import CallInHours from "@modules/account/components/hat/CallInHours";

const CallHours: React.FC = () => {
  // if (appData.site.workingDayTimeNow) {
  if (false) {
    return <CallAfterHours />;
  } else {
    return <CallInHours />;
  }
};

const TopLine: React.FC = () => {
  return (
    <div className="top-header show-for-large">
      <div className="container">
        <div className="row">
          <div className="col-sm-4 d-flex">
            {/*<span className="top-line-site-name">{appData.site.shortName}</span>*/}
            <span className="top-line-site-name">artist</span>
          </div>
          <div className="col-sm-8">
            <div className="align-items-start call_lang d-flex justify-content-end">
              <CallHours />
              <a href="#" className="top-line-lang-icon top-line_lang-icon"></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default TopLine;
