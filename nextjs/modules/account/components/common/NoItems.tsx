import React from "react";
import Styles from "@modules/account/components/common/NoItems.module.scss";
import IconNoItems from "@modules/icon/components/account/NoItems";
import cn from "classnames";

interface IProps {
  message: string;
}

const NoItems: React.FC<IProps> = function (props: IProps) {
  const { message } = props;

  return (
    <div className="no-items-block-container">
      <IconNoItems className={cn(Styles.icon, "mb-10")} />
      <div className={"no-items-block-text"}>{message}</div>
    </div>
  );
};

export default NoItems;
