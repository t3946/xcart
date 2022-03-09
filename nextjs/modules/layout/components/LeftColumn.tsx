import React from "react";
import Styles from "@modules/layout/components/LeftColumn.module.scss";
import cn from "classnames";

interface IProps {
  className: any;
  children?: any;
}

const LeftColumn: React.FC<IProps> = React.forwardRef(
  (props: IProps, ref: any) => {
    const classes = [Styles.container, props.className];

    return (
      <div className={cn(classes)} ref={ref}>
        {props.children}
      </div>
    );
  }
);

export default LeftColumn;
