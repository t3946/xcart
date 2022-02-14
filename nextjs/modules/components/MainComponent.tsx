import React from "react";
import cn from "classnames";
import Styles from "@modules/components/MainComponent.module.scss";

const MainComponent: React.FC = ({ children }) => {
  return (
    <div className={cn(Styles.account__mainWrapper, Styles.accountMainWrapper)}>
      {children}
    </div>
  );
};

export default MainComponent;
