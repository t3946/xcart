import React from "react";
import { useDispatch } from "react-redux";
import DepartmentsMenuMobile from "@modules/account/components/hat/DepartmentsMenuMobile";
import HatNavigation from "@modules/account/components/hat/HatNavigation";
import HatSearchLine from "@modules/account/components/hat/HatSearchLine";
import MenuMobile from "@modules/account/components/hat/MenuMobile";
import Snackbar from "@modules/account/components/snackbar/Snackbar";
import BreadCrumbs from "@modules/account/components/bread-crumbs/BreadCrumbs";
import _merge from "lodash/merge";

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
      <Snackbar>
        <DepartmentsMenuMobile />
        <HatNavigation />
        <HatSearchLine />
        <MenuMobile />

        <div className="container">
          {showBreadcrumbs && <BreadCrumbs />}

          <div className="row mt-lg-20">{props.children}</div>
        </div>
      </Snackbar>
    </>
  );
};

export default Page;
