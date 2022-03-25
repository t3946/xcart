import React from "react";
import CallAfterHours from "@modules/account/components/hat/CallAfterHours";
import CallInHours from "@modules/account/components/hat/CallInHours";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";

const TopLine: React.FC = () => {
  const config = useSelectorAccount((e) => e.config);
  return (
    <div className="top-header show-for-large">
      <div className="container">
        <div className="row">
          <div className="col-sm-4 d-flex">
            <span className="top-line-site-name">{config.site.shortName}</span>
          </div>
          <div className="col-sm-8">
            <div className="align-items-start call_lang d-flex justify-content-end">
              {config.site.workingDayTimeNow ? (
                <CallAfterHours />
              ) : (
                <CallInHours />
              )}
              <a href="#" className="top-line-lang-icon top-line_lang-icon"></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default TopLine;
