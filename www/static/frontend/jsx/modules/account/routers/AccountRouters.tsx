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
import MenuMobile from "@client/jsx/modules/account/components/hat/MenuMobile";
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
import AlertMobile from "@client/modules/account/components/shared/AlertMobile";
import { EditInfoInListProductPage } from "@client/modules/account/pages/EditInfoInListProductPage";
import { ManageListPage } from "@client/modules/account/pages/ManageListPage";
import { ShareListPage } from "@client/modules/account/pages/ShareListPage";
import { DeleteListPage } from "@client/modules/account/pages/DeleteListPage";
import { AddListPage } from "@client/modules/account/pages/AddListPage";
import { AddIdeaPage } from "@client/modules/account/pages/AddIdeaPage";
import { AddProductToList } from "@client/modules/account/components/lists/AddProductToList";
import { AddProductToListPage } from "@client/modules/account/pages/AddProductToListPage";
import { MoveProductPage } from "@client/modules/account/pages/MoveProductPage";
import { DashboardPage } from "@client/modules/account/pages/DashboardPage";

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

  return (
    <>
      <ShadowPanel />
      <Snackbar>
        <BrowserRouter>
          <DepartmentsMenuMobile />
          <HatNavigation />
          <HatSearchLine isStatic={true} />
          <MenuMobile />
          <AlertMobile />

          <div className={"container"}>
            {user && <BreadCrumbs />}

            <div className="row mt-lg-20">
              <Switch>
                <Route
                  exact
                  path="/account/"
                  component={PageContainerHoc(
                    <SideBarMenu />,
                    <DashboardPage />
                  )}
                />
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
                  path="/account/your-lists/edit-list-product-info/:listHash/:productId"
                  component={PageContainerHoc(
                    <ListsSidebarMenu />,
                    <EditInfoInListProductPage />
                  )}
                />
                <Route
                  exact
                  path="/account/your-lists/manage-list/:listHash"
                  component={PageContainerHoc(
                    <ListsSidebarMenu />,
                    <ManageListPage />
                  )}
                />
                <Route
                  exact
                  path="/account/your-lists/:id/share-list"
                  component={PageContainerHoc(
                    <ListsSidebarMenu />,
                    <ShareListPage />
                  )}
                />
                <Route
                  exact
                  path="/account/your-lists/:listHash/delete-list"
                  component={PageContainerHoc(
                    <ListsSidebarMenu />,
                    <DeleteListPage />
                  )}
                />
                <Route
                  exact
                  path="/account/your-lists/add-list/:productId?"
                  component={PageContainerHoc(
                    <ListsSidebarMenu />,
                    <AddListPage />
                  )}
                />
                <Route
                  exact
                  path="/account/your-lists/move-product/:productId/:listId"
                  component={PageContainerHoc(
                    <ListsSidebarMenu />,
                    <MoveProductPage />
                  )}
                />
                <Route
                  exact
                  path="/account/your-lists/add-idea/:listHash"
                  component={PageContainerHoc(
                    <ListsSidebarMenu />,
                    <AddIdeaPage />
                  )}
                />
                <Route
                  exact
                  path="/account/your-lists/invite/*"
                  component={PageContainerHoc(
                    <ListsSidebarMenu />,
                    <InvitationPage />
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
                  path="/account/your-lists/add-product-to-list/:isAdded/:listId/:sku"
                  component={PageContainerHoc(
                    <ListsSidebarMenu />,
                    <AddProductToListPage />
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
    </>
  );
};
