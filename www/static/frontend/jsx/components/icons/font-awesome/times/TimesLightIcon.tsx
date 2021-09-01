import React from "react";
import classNames from "classnames";

const TimesLightIcon = (props: Record<any, any>): any => {
  return (
    <i className={classNames("common-icon", props.className)}>
      <svg
        version="1.1"
        id="Слой_1"
        focusable="false"
        xmlns="http://www.w3.org/2000/svg"
        xmlnsXlink="http://www.w3.org/1999/xlink"
        x="0px"
        y="0px"
        viewBox="0 0 320 320.7"
        fill={"currentColor"}
      >
        <path d="M193.9,160.7L296.5,58.1L317.6,37c3.1-3.1,3.1-8.2,0-11.3L295,3c-3.1-3.1-8.2-3.1-11.3,0L160,126.7L36.3,3 C33.2-0.1,28.1-0.1,25,3L2.3,25.6c-3.1,3.1-3.1,8.2,0,11.3l123.7,123.7L2.3,284.4c-3.1,3.1-3.1,8.2,0,11.3L25,318.3 c3.1,3.1,8.2,3.1,11.3,0L160,194.6l102.6,102.6l21.1,21.1c3.1,3.1,8.2,3.1,11.3,0l22.6-22.6c3.1-3.1,3.1-8.2,0-11.3L193.9,160.7z" />
      </svg>
    </i>
  );
};

export default TimesLightIcon;
