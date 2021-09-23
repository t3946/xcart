import React from "react";
import classNames from "classnames";

const Icon: React.FC<any> = function (props: any) {
  return (
    <i className={classNames("d-inline-block", props.className)}>
      {props.children}
    </i>
  );
};

export default Icon;
