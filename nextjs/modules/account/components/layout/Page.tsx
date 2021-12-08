import React from "react";
import { useDispatch } from "react-redux";
import HatNavigation from "@modules/account/components/hat/HatNavigation";
import HatSearchLine from "@modules/account/components/hat/HatSearchLine";
import MenuMobile from "@modules/account/components/hat/MenuMobile";
import { setBreadcrumbsAddresses } from "@redux/actions/account-actions/BreadcrumbsActions";
import { staticRoutes } from "@modules/account/ts/consts/breadcrumbs";
import Snackbar from "@modules/account/components/snackbar/Snackbar";
import SideBarMenu from "@modules/account/components/sidebar-menu/SideBarMenu";
import cn from "classnames";
import BreadCrumbs from "@modules/account/components/bread-crumbs/BreadCrumbs";

const Page: React.FC = (props): any => {
  const dispatch = useDispatch();
  dispatch(setBreadcrumbsAddresses(staticRoutes));

  const classes = {
    leftColumnClasses: ["col account-page-left-column d-none", "d-lg-block"],
    rightColumnClasses: ["col", "account-page-right-column"],
  };

  return (
    <>
      <Snackbar>
        <HatNavigation />
        <HatSearchLine isStatic={true} />
        <MenuMobile />

        <div className="container">
          <BreadCrumbs />

          <div className="row mt-lg-20">
            <div className={cn(classes.leftColumnClasses)}>
              <SideBarMenu />
            </div>

            <div className={cn(classes.rightColumnClasses)}>
              {props.children}
            </div>
          </div>
        </div>
      </Snackbar>
    </>
  );
};

export default Page;
