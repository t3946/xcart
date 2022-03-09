import React from "react";
import classnames from "classnames";
import Styles from "@modules/components/Badge/Badge.module.scss";

interface IProps {
  text?: string | number;
  className?: string;
}

const Badge: React.FC<IProps> = (props: IProps) => {
  const { text, className } = props;
  const classes = [
    "sidebar-badge",
    "d-flex",
    "align-items-center",
    "justify-content-center",
    "fw-bold",
    Styles.badge,
    className,
  ];

  return <div className={classnames(classes)}>{text}</div>;
};

export default Badge;
