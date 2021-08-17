import React from "react";
import { BrowserRouter, Route, Switch } from "react-router-dom";
import { AddAddressDialog } from "../components/addresses/AddAddressDialog";
import { BreadCrumbs } from "../components/bread-crubms/BreadCrumbs";
import { AddressDialogHOC } from "../hoc/AddressDialogHOC";
import { Addresses } from "../pages/Addresses";
import { Transactions } from "../pages/Transactions";
import { Wallet } from "../pages/Wallet";
import { Provider, useDispatch, useSelector } from "react-redux";
import { accountStore } from "../../../redux/stores/StoreAccount";
import LoginForm from "../../account/components/authorization/LoginForm";
import RegisterForm from "../../account/components/authorization/RegisterForm";
import { AddAddressPage } from "../pages/AddAddressPage";
import HatNavigation from "../components/hat/HatNavigation";
import HatSearchLine from "../components/hat/HatSearchLine";
import MobileMenu from "../components/hat/MobileMenu";
import SideBarMenu from "../components/sidebar-menu/SideBarMenu";
import { StoreDto } from "@s3stores-mail/ts/types";
import classNames from "classnames";
import PublicProfile from "../components/public-profile/PublicProfile";
import { setBreadcrumbsAddresses } from "../../../redux/actions/account-actions/BreadcrumbsActions";
import { staticRoutes } from "../ts/consts/breadcrumbs";
import ShadowPanel from "@client/modules/account/components/shared/ShadowPanel";
import LoginAndSecurity from "@client/modules/account/components/login-and-security/LoginAndSecurity";
import Snackbar from "@client/jsx/modules/account/components/snackbar/Snackbar";

export const AccountRouters = (): any => {
  const dispatch = useDispatch();
  const user = useSelector((e: StoreDto) => e.user);

  dispatch(setBreadcrumbsAddresses(staticRoutes));

  const leftColumnClasses = [
    "col account-page-left-column d-none",
    {
      "d-lg-block": user !== null,
    },
  ];

  const rightColumnClasses = [
    "col",
    {
      "account-page-right-column": user !== null,
      "d-flex": user === null,
      "justify-content-center": user === null,
    },
  ];

  return (
    <Provider store={accountStore as any}>
      <Snackbar>
        <BrowserRouter>
          <ShadowPanel />
          <HatNavigation />
          <HatSearchLine />
          <MobileMenu />

          <div className={"container"}>
            {user && <BreadCrumbs />}

            <div className="row">
              <div className={classNames(leftColumnClasses)}>
                <SideBarMenu />
              </div>

              <div className={classNames(rightColumnClasses)}>
                <Switch>
                  <Route
                    exact
                    path="/account/addresses"
                    component={AddressDialogHOC(
                      <Addresses />,
                      <AddAddressDialog />
                    )}
                  />

                  <Route
                    exact
                    path="/account/addresses/add"
                    component={AddAddressPage}
                  />

                  <Route
                    exact
                    path="/account/payments/wallet"
                    component={Wallet}
                  />

                  <Route
                    exact
                    path="/account/payments/transactions"
                    component={Transactions}
                  />

                  <Route
                    exact
                    path={appData.routes["account:login"]}
                    component={LoginForm}
                  />

                  <Route
                    exact
                    path={appData.routes["account:register"]}
                    component={RegisterForm}
                  />

                  <Route
                    exact
                    path={appData.routes["account:public-profile"]}
                    component={PublicProfile}
                  />

                  <Route
                    exact
                    path={appData.routes["account:login-and-security"]}
                    component={LoginAndSecurity}
                  />
                </Switch>
              </div>
            </div>
          </div>
        </BrowserRouter>
      </Snackbar>
    </Provider>
  );
};
