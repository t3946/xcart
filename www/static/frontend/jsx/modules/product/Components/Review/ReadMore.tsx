import React from "react";
import ArrowIcon from "@client/modules/icon/components/account/chevron-down/AccountSidebarTablet";
import classnames from "classnames";

interface PropsInterface {
  setIsOpen: (isOpen: boolean) => void;
  isOpen: boolean;
  classes: {
    icon: any;
  };
}

const ReadMore: React.FC<PropsInterface> = function (
  props: PropsInterface
) {
  return (
    <div
      className="d-flex align-items-center common-link common-link_spoiler mt-12"
      onClick={() => {
        props.setIsOpen(!props.isOpen);
      }}
    >
      <ArrowIcon className={classnames(props.classes.icon)} />{" "}
      <span>Read more</span>
    </div>
  );
};

export default ReadMore;
