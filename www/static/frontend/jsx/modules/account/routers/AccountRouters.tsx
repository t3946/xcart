import React, { useEffect } from "react";
import { BrowserRouter, Route, Switch } from "react-router-dom";
import { BreadCrumbs } from "../components/bread-crubms/BreadCrumbs";
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
import { EditCard } from "@client/modules/account/pages/EditCard";
import { AddCard } from "@client/modules/account/pages/AddCard";
import { RemoveCardPage } from "@client/modules/account/pages/RemoveCardPage";
import { PageContainerHoc } from "@client/modules/account/hoc/PageContainerHoc";
import { InvitationPage } from "../pages/InvitationPage";
import TSVDisable from "@client/modules/account/components/login-and-security/TSVDisable";
import TSVChangePreferredMethod from "@client/modules/account/components/login-and-security/TSVChangePreferredMethod";
import TSVRecovery from "@client/modules/account/components/login-and-security/TSVRecovery";
import PasswordAssistance from "@client/modules/account/components/password-assistance/PasswordAssistance";

export const AccountRouters = (): any => {
  const dispatch = useDispatch();
  const user = useSelector((e: AccountStore) => e.user);

  useEffect(() => {
    dispatch(getTerritory());

    if (accountStore.getState().user) {
      dispatch(getAddresses(accountStore.getState().user.id));
    }
  }, []);

  dispatch(setBreadcrumbsAddresses(staticRoutes));

  return (
    <Provider store={accountStore as any}>
      <Snackbar>
        <BrowserRouter>
          <DepartmentsMenuMobile
            classes={{ container: "hat-navigation_departments-menu-mobile" }}
          />
          <ShadowPanel />
          <HatNavigation />
          <HatSearchLine />
          <MobileMenu />

          <div className={"container"}>
            {user && <BreadCrumbs />}

            <div className="row mt-lg-20">
              <Switch>
                <Route
                  exact
                  path="/account/addresses"
                  component={PageContainerHoc(<SideBarMenu />, <Addresses />)}
                />
                <Route
                  exact
                  path="/account/addresses/add"
                  component={PageContainerHoc(
                    <SideBarMenu />,
                    <AddAddressPage />
                  )}
                />
                <Route
                  exact
                  path="/account/addresses/edit"
                  component={PageContainerHoc(
                    <SideBarMenu />,
                    <AddAddressPage />
                  )}
                />
                <Route
                  exact
                  path="/account/payments/wallet"
                  component={PageContainerHoc(<SideBarMenu />, <Wallet />)}
                />
                <Route
                  exact
                  path="/account/payments/wallet/edit"
                  component={PageContainerHoc(<SideBarMenu />, <EditCard />)}
                />
                <Route
                  exact
                  path="/account/payments/wallet/add"
                  component={PageContainerHoc(<SideBarMenu />, <AddCard />)}
                />
                <Route
                  exact
                  path="/account/payments/wallet/remove"
                  component={PageContainerHoc(
                    <SideBarMenu />,
                    <RemoveCardPage />
                  )}
                />
                <Route
                  exact
                  path="/account/payments/transactions"
                  component={PageContainerHoc(
                    <SideBarMenu />,
                    <Transactions />
                  )}
                />
                <Route
                  exact
                  path="/account/your-lists/:id"
                  component={PageContainerHoc(
                    <ListsSidebarMenu />,
                    <ListsPage />
                  )}
                />
                <Route
                  exact
                  path="/account/your-lists"
                  component={PageContainerHoc(
                    <ListsSidebarMenu />,
                    <ListsPage />
                  )}
                />
                http://localhost/account/your-lists/invite/dfjU7G93+2udow==
                <Route
                  exact
                  path="/account/your-lists/invite/:encryptUrl/:tag"
                  component={PageContainerHoc(
                    <ListsSidebarMenu />,
                    <InvitationPage />
                  )}
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
                  component={PageContainerHoc(
                    <SideBarMenu />,
                    <PublicProfile />
                  )}
                />
                <Route
                  exact
                  path={route("account:login-and-security")}
                  component={PageContainerHoc(
                    <SideBarMenu />,
                    <LoginAndSecurity />
                  )}
                />
                <Route
                  exact
                  path={route("account:edit-name")}
                  component={PageContainerHoc(
                    <SideBarMenu />,
                    <FormEditUserName />
                  )}
                />
                <Route
                  exact
                  path={route("account:edit-email")}
                  component={PageContainerHoc(
                    <SideBarMenu />,
                    <FormEditUserEmail />
                  )}
                />
                <Route
                  exact
                  path={route("account:edit-phone")}
                  component={PageContainerHoc(
                    <SideBarMenu />,
                    <FormEditUserPhone />
                  )}
                />
                <Route
                  exact
                  path={route("account:edit-password")}
                  component={PageContainerHoc(
                    <SideBarMenu />,
                    <FormChangePassword />
                  )}
                />
                <Route
                  exact
                  path={route("account:two-step-verification-settings")}
                  component={PageContainerHoc(<SideBarMenu />, <TSVSettings />)}
                />
                <Route
                  exact
                  path={route("account:two-step-verification-add-new")}
                  component={PageContainerHoc(
                    <SideBarMenu />,
                    <TSVAddNewApp />
                  )}
                />
                <Route
                  exact
                  path={route("account:two-step-verification-settings-disable")}
                  component={TSVDisable}
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
        </BrowserRouter>
      </Snackbar>
    </Provider>
  );
};
