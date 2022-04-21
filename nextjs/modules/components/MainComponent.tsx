import React from "react";
import cn from "classnames";
import Styles from "@modules/components/MainComponent.module.scss";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import TagManager from "react-gtm-module";

const MainComponent: React.FC = ({ children }) => {
  const isVisibleMobileMenu = useSelectorAccount(
    (state) => state.departmentsMenuMobile.isVisible
  );

  const tagManagerArgs = {
    gtmId: "GTM-TCNTJMM",
  };

  if (typeof document !== "undefined") {
    TagManager.initialize(tagManagerArgs);
  }

  return (
    <div
      className={cn(Styles.accountMainWrapper, {
        [Styles.accountMainWrapper_shifted]: isVisibleMobileMenu,
      })}
    >
      {children}
    </div>
  );
};

export default MainComponent;
