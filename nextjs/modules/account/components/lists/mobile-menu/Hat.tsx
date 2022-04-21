import * as React from "react";
import cn from "classnames";
import Styles from "@modules/account/components/lists/mobile-menu/MenuHat.module.scss";

interface IProps {
  columnLeft: any;
  columnRight: any;
}

export const Hat: React.FC<IProps> = function (props) {
  const { columnLeft, columnRight } = props;

  return (
    <div className={cn(Styles.container, "d-flex")}>
      <div className={Styles.columnLeft}>{columnLeft}</div>
      <div className={Styles.columnRight}>{columnRight}</div>
    </div>
  );
};

export default Hat;
