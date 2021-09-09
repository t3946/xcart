import React from "react";
import classNames from "classnames";

export const PageContainerHoc = (leftColumnComponent, rightColumnComponent) => {
  return () => {
    const leftColumnClasses = [
      "col account-page-left-column d-none",
      "d-lg-block",
    ];

    const rightColumnClasses = ["col", "account-page-right-column"];
    return (
      <React.Fragment>
        <div className={classNames(leftColumnClasses)}>
          {leftColumnComponent}
        </div>
        <div className={classNames(rightColumnClasses)}>
          {rightColumnComponent}
        </div>
      </React.Fragment>
    );
  };
};
