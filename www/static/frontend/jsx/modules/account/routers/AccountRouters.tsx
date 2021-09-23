import React, { useEffect } from "react";
import { BrowserRouter, Route, Switch, Link } from "react-router-dom";
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
import { getTerritory } from "../../../redux/actions/account-actions/MainActions";
import HatNavigation from "../components/hat/HatNavigation";
import HatSearchLine from "../components/hat/HatSearchLine";
import MobileMenu from "../components/hat/MobileMenu";
import SideBarMenu from "../components/sidebar-menu/SideBarMenu";
import { getAddresses } from "../../../redux/actions/account-actions/AddressActions";
import classnames from "classnames";
import useBreakpoint from "../hooks/useBreakpoint";
import PublicProfile from "../components/public-profile/PublicProfile";
import { setBreadcrumbsAddresses } from "../../../redux/actions/account-actions/BreadcrumbsActions";
import { staticRoutes } from "../ts/consts/breadcrumbs";
import ShadowPanel from "@client/modules/account/components/shared/ShadowPanel";
import LoginAndSecurity from "@client/modules/account/components/login-and-security/LoginAndSecurity";
import FormEditUserName from "@client/modules/account/components/login-and-security/FormEditUserName";
import FormEditUserEmail from "@client/modules/account/components/login-and-security/FormEditUserEmail";
import FormEditUserPhone from "@client/modules/account/components/login-and-security/FormEditUserPhone";
import FormChangePassword from "@client/modules/account/components/login-and-security/FormChangePassword";
import Snackbar from "@client/jsx/modules/account/components/snackbar/Snackbar";
import { route } from "@client/jsx/utils/AppData";
import { ListsSidebarMenu } from "../components/lists/ListsSidebarMenu";
import { AccountStore } from "../ts/types/account-store.type";
import { ListsPage } from "../pages/ListsPage";
import DepartmentsMenuMobile from "@client/modules/account/components/hat/DepartmentsMenuMobile";
import TSVSettings from "@client/modules/account/components/login-and-security/TSVSettings";
import TSVAddNewApp from "@client/modules/account/components/login-and-security/TSVAddNewApp";
import TSVDisable from "@client/modules/account/components/login-and-security/TSVDisable";
import TSVChangePreferredMethod from "@client/modules/account/components/login-and-security/TSVChangePreferredMethod";
import TSVRecovery from "@client/modules/account/components/login-and-security/TSVRecovery";
import PasswordAssistance from "@client/modules/account/components/password-assistance/PasswordAssistance";
import AlertMobile from "@client/modules/account/components/shared/AlertMobile";

export const AccountRouters = (): any => {
  const dispatch = useDispatch();
  const user = useSelector((e: AccountStore) => e.user);
  const isList = useSelector((e: AccountStore) => e.main.isList);

  useEffect(() => {
    useBreakpoint();
    dispatch(getTerritory());

    if (accountStore.getState().user) {
      dispatch(getAddresses(accountStore.getState().user.id));
    }
  }, []);

  dispatch(setBreadcrumbsAddresses(staticRoutes));

  const classes = {
    leftColumnClasses: [
      "col account-page-left-column d-none",
      {
        "d-lg-block": user !== null,
      },
    ],
    rightColumnClasses: [
      "col",
      {
        "account-page-right-column": user !== null,
        "d-flex": user === null,
        "justify-content-center": user === null,
      },
    ],
  };

  function leftColumnTemplate() {
    if (isList) {
      return <ListsSidebarMenu />;
    } else {
      return (
        <>
          <SideBarMenu />
          <div className={"leave-feedback text-center mt-12"}>
            <Link to={"/account"} className="common-link">
              Leave feedback
            </Link>
          </div>
        </>
      );
    }
  }

  return (
    <>
      <ShadowPanel />
      <Snackbar>
        <BrowserRouter>
          <DepartmentsMenuMobile
            classes={{ container: "hat-navigation_departments-menu-mobile" }}
          />
          <HatNavigation />
          <HatSearchLine />
          <MobileMenu />
          <AlertMobile />

          <div className={"container"}>
            {user && <BreadCrumbs />}

            <div className="row">
              <div className={classnames(classes.leftColumnClasses)}>
                {leftColumnTemplate()}
              </div>

              <div className={classnames(classes.rightColumnClasses)}>
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
                    path="/account/your-lists/:id"
                    component={ListsPage}
                  />
                  <Route
                    exact
                    path="/account/your-lists"
                    component={ListsPage}
                  />

                  <Route
                    exact
                    path={route("account:login")}
                    component={LoginForm}
                  />

                  <Route
                    exact
                    path={route("account:register")}
                    component={RegisterForm}
                  />

                  <Route
                    exact
                    path={route("account:public-profile")}
                    component={PublicProfile}
                  />

                  <Route
                    exact
                    path={route("account:login-and-security")}
                    component={LoginAndSecurity}
                  />

                  <Route
                    exact
                    path={route("account:edit-name")}
                    component={FormEditUserName}
                  />

                  <Route
                    exact
                    path={route("account:edit-email")}
                    component={FormEditUserEmail}
                  />

                  <Route
                    exact
                    path={route("account:edit-phone")}
                    component={FormEditUserPhone}
                  />

                  <Route
                    exact
                    path={route("account:edit-password")}
                    component={FormChangePassword}
                  />

                  <Route
                    exact
                    path={route("account:two-step-verification-settings")}
                    component={TSVSettings}
                  />

                  <Route
                    exact
                    path={route(
                      "account:two-step-verification-settings-disable"
                    )}
                    component={TSVDisable}
                  />

                  <Route
                    exact
                    path={route("account:two-step-verification-add-new")}
                    component={TSVAddNewApp}
                  />

                  <Route
                    exact
                    path={route(
                      "account:two-step-verification-settings-preferred-method"
                    )}
                    component={TSVChangePreferredMethod}
                  />

                  <Route
                    exact
                    path={route("account:two-step-verification-recovery")}
                    component={TSVRecovery}
                  />

                  <Route
                    exact
                    path={route(
                      "account:two-step-verification-recovery-password-assistance"
                    )}
                    component={PasswordAssistance}
                  />
                </Switch>
              </div>
            </div>
          </div>
        </BrowserRouter>
      </Snackbar>
    </>
  );
};
