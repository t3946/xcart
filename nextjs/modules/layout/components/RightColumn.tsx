import React from "react";
import Styles from "@modules/layout/components/RightColumn.module.scss";
import cn from "classnames";

interface IProps {
  className: any;
  children?: any;
}

const RightColumn: React.FC<IProps> = (props: IProps) => {
  const classes = [Styles.container, props.className];

  return <div className={cn(classes)}>{props.children}</div>;
};

export default RightColumn;
