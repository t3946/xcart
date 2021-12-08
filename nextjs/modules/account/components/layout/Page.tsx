import React, { useEffect } from "react";
import { useDispatch } from "react-redux";
import useSelectorAccount from "@modules/account/hooks/useSelectorAccount";
import { getTerritory } from "@redux/actions/account-actions/MainActions";
import HatNavigation from "@modules/account/components/hat/HatNavigation";
import HatSearchLine from "@modules/account/components/hat/HatSearchLine";
import MenuMobile from "@modules/account/components/hat/MenuMobile";
import { getAddresses } from "@redux/actions/account-actions/AddressActions";
import { setBreadcrumbsAddresses } from "@redux/actions/account-actions/BreadcrumbsActions";
import { staticRoutes } from "@modules/account/ts/consts/breadcrumbs";
import Snackbar from "@modules/account/components/snackbar/Snackbar";
import SideBarMenu from "@modules/account/components/sidebar-menu/SideBarMenu";
import PageContainerHoc from "@modules/account/hoc/PageContainerHoc";
import cn from "classnames";
import BreadCrumbs from "@modules/account/components/bread-crumbs/BreadCrumbs";

const Page: React.FC = (props): any => {
  const dispatch = useDispatch();
  // const user = useSelectorAccount((e) => e.user);

  // useEffect(() => {
  //   dispatch(getTerritory());
  //
  //   if (user) {
  //     dispatch(getAddresses(user.user_id));
  //   }
  // }, []);
  //
  dispatch(setBreadcrumbsAddresses(staticRoutes));

  const classes = {
    leftColumnClasses: ["col account-page-left-column d-none", "d-lg-block"],
    rightColumnClasses: ["col", "account-page-right-column"],
  };

  return (
    <>
      {/*<ShadowPanel />*/}
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
