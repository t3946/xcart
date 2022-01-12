import React from "react";
import DepartmentsMenuMobile from "@modules/account/components/hat/DepartmentsMenuMobile";
import HatNavigation from "@modules/account/components/hat/HatNavigation";
import HatSearchLine from "@modules/account/components/hat/HatSearchLine";
import MenuMobile from "@modules/account/components/hat/MenuMobile";
import Snackbar from "@modules/account/components/snackbar/Snackbar";
import BreadCrumbs from "@modules/account/components/bread-crumbs/BreadCrumbs";
import _merge from "lodash/merge";
import ShadowPanel from "@modules/account/components/shared/ShadowPanel";

interface IProps {
  showBreadcrumbs?: boolean;
  children?: any;
}
const Page: React.FC<IProps> = (props: IProps): any => {
  const defaultProps: IProps = {
    showBreadcrumbs: true,
  };
  const { showBreadcrumbs } = _merge(defaultProps, props);

  return (
    <>
      <ShadowPanel />
      <Snackbar>
        <DepartmentsMenuMobile />
        <HatNavigation />
        <HatSearchLine />
        <MenuMobile />

        <div className="container-lg">
          {showBreadcrumbs && <BreadCrumbs />}

          <div className="row mt-lg-20">{props.children}</div>
        </div>
      </Snackbar>
    </>
  );
};

export default Page;
