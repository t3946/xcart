import React from "react";
import Styles from "@modules/account/components/common/NoItems.module.scss";
import IconNoItems from "@modules/icon/components/account/NoItems";
import cn from "classnames";

interface IProps {
  message?: string;
  children: any;
}

const NoItems: React.FC<IProps> = function (props: IProps) {
  const { message } = props;

  return (
    <div className="no-items-block-container">
      <IconNoItems className={cn(Styles.icon, "mb-10")} />
      {message && <div className={"no-items-block-text"}>{message}</div>}
      {props.children}
    </div>
  );
};

export default NoItems;
