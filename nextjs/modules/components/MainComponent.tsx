import React from "react";
import cn from "classnames";
import Styles from "@modules/components/MainComponent.module.scss";
import { useSelector } from "react-redux";
import { AccountStore } from "@modules/account/ts/types/store.type";

const MainComponent: React.FC = ({ children }) => {
  const isVisibleMobileMenu = useSelector(
    (state: AccountStore) => state.departmentsMenuMobile.isVisible
  );

  return (
    <div className={Styles.root}>
      <div
        className={cn(Styles.account__mainWrapper, Styles.accountMainWrapper, {
          [Styles.accountMainWrapper__mainWrapperShifted]: isVisibleMobileMenu,
        })}
      >
        {children}
      </div>
    </div>
  );
};

export default MainComponent;
