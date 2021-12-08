import React from "react";
import SideBarMenu from "@modules/account/components/sidebar-menu/SideBarMenu";
import cn from "classnames";
import Page from "@modules/account/components/layout/Page";

const PageTwoColumns: React.FC = (props): any => {
  const classes = {
    leftColumnClasses: ["col account-page-left-column d-none", "d-lg-block"],
    rightColumnClasses: ["col", "account-page-right-column"],
  };

  return (
    <Page>
      <div className={cn(classes.leftColumnClasses)}>
        <SideBarMenu />
      </div>

      <div className={cn(classes.rightColumnClasses)}>
        {props.children}
      </div>
    </Page>
  );
};

export default PageTwoColumns;
