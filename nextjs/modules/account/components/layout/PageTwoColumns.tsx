import React from "react";
import SideBarMenu from "@modules/account/components/sidebar-menu/SideBarMenu";
import cn from "classnames";
import Page from "@modules/account/components/layout/Page";
import Styles from "@modules/account/components/layout/PageTwoColumns.module.scss";
import Snackbar from "@modules/account/components/shared/Snackbar";

interface PageTwoColumns {
  bar?: any;
}

const PageTwoColumns: React.FC<PageTwoColumns> = ({
  children,
  bar = <SideBarMenu />,
}): any => {
  const classes = {
    leftColumnClasses: ["col account-page-left-column d-none", "d-lg-block"],
    rightColumnClasses: [
      "col",
      "account-page-right-column",
      Styles.rightColumn,
    ],
  };

  return (
    <Page>
      <div className={cn(classes.leftColumnClasses)}>{bar}</div>
      <div className={cn(classes.rightColumnClasses)}>
        <Snackbar />
        {children}
      </div>
    </Page>
  );
};

export default PageTwoColumns;
