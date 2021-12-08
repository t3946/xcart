import React from "react";
import { useDispatch } from "react-redux";
import HatNavigation from "@modules/account/components/hat/HatNavigation";
import HatSearchLine from "@modules/account/components/hat/HatSearchLine";
import MenuMobile from "@modules/account/components/hat/MenuMobile";
import { setBreadcrumbsAddresses } from "@redux/actions/account-actions/BreadcrumbsActions";
import { staticRoutes } from "@modules/account/ts/consts/breadcrumbs";
import Snackbar from "@modules/account/components/snackbar/Snackbar";
import BreadCrumbs from "@modules/account/components/bread-crumbs/BreadCrumbs";
import _merge from "lodash/merge";

interface IProps {
  showBreadcrumbs?: boolean;
}
const Page: React.FC<IProps> = (props: IProps): any => {
  const dispatch = useDispatch();
  const defaultProps: IProps = {
    showBreadcrumbs: true,
  };
  const { showBreadcrumbs } = _merge(defaultProps, props);

  dispatch(setBreadcrumbsAddresses(staticRoutes));

  return (
    <>
      <Snackbar>
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
