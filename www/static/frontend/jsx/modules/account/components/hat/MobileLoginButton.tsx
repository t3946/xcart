import classNames from "classnames";
import React from "react";

const MobileTemplate: React.FC<any> = () => {
  const classes = [
    "navigation-login-button d-md-none common-icon d-flex align-items-center",
  ];

  if (appData.user) {
    classes.push("navigation-login-button__logged");
  } else {
    classes.push("navigation-login-button__not-logged");
  }

  return <i className={classNames(classes)} />;
};

export default MobileTemplate;
