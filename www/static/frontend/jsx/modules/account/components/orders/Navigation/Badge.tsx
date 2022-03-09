import React from "react";
import classnames from "classnames";

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
    "rounded-pill",
    "fw-bold",
    className,
  ];

  return <div className={classnames(classes)}>{text}</div>;
};

export default Badge;
