import React, { useEffect } from "react";
// import { BreadCrumbs } from "../components/bread-crubms/BreadCrumbs";
import { useDispatch, useSelector } from "react-redux";
import Store from "@redux/stores/Store";
import { getTerritory } from "@redux/actions/account-actions/MainActions";
import HatNavigation from "@modules/account/components/hat/HatNavigation";
import HatSearchLine from "@modules/account/components/hat/HatSearchLine";
import MenuMobile from "@modules/account/components/hat/MenuMobile";
import { getAddresses } from "@redux/actions/account-actions/AddressActions";
import { setBreadcrumbsAddresses } from "@redux/actions/account-actions/BreadcrumbsActions";
import { staticRoutes } from "@modules/account/ts/consts/breadcrumbs";
// import ShadowPanel from "@modules/account/components/shared/ShadowPanel";
import Snackbar from "@modules/account/components/snackbar/Snackbar";
import StoreInterface from "@modules/account/ts/types/store.type";
// import DepartmentsMenuMobile from "@modules/account/components/hat/DepartmentsMenuMobile";
// import AlertMobile from "@modules/account/components/shared/AlertMobile";
// import {PageContainerHoc} from "../../../../www/static/frontend/jsx/modules/account/hoc/PageContainerHoc";
// import SideBarMenu from "../../../../www/static/frontend/jsx/modules/account/components/sidebar-menu/SideBarMenu";
// import {DashboardPage} from "../../../../www/static/frontend/jsx/modules/account/pages/DashboardPage";
// import {Addresses} from "../../../../www/static/frontend/jsx/modules/account/pages/Addresses";
// import {AddAddressPage} from "../../../../www/static/frontend/jsx/modules/account/pages/AddAddressPage";
// import {Wallet} from "../../../../www/static/frontend/jsx/modules/account/pages/Wallet";
// import {EditCard} from "../../../../www/static/frontend/jsx/modules/account/pages/EditCard";
// import {AddCard} from "../../../../www/static/frontend/jsx/modules/account/pages/AddCard";
// import {RemoveCardPage} from "../../../../www/static/frontend/jsx/modules/account/pages/RemoveCardPage";
// import {Transactions} from "../../../../www/static/frontend/jsx/modules/account/pages/Transactions";
// import {ListsSidebarMenu} from "../../../../www/static/frontend/jsx/modules/account/components/lists/ListsSidebarMenu";
// import {DeleteProductPage} from "../../../../www/static/frontend/jsx/modules/account/pages/DeleteProductPage";
// import {EditInfoInListProductPage} from "../../../../www/static/frontend/jsx/modules/account/pages/EditInfoInListProductPage";
// import {ManageListPage} from "../../../../www/static/frontend/jsx/modules/account/pages/ManageListPage";
// import {ShareListPage} from "../../../../www/static/frontend/jsx/modules/account/pages/ShareListPage";
// import {DeleteListPage} from "../../../../www/static/frontend/jsx/modules/account/pages/DeleteListPage";
// import {AddListPage} from "../../../../www/static/frontend/jsx/modules/account/pages/AddListPage";
// import {MoveProductPage} from "../../../../www/static/frontend/jsx/modules/account/pages/MoveProductPage";
// import {AddIdeaPage} from "../../../../www/static/frontend/jsx/modules/account/pages/AddIdeaPage";
// import {InvitationPage} from "../../../../www/static/frontend/jsx/modules/account/pages/InvitationPage";
// import {ListsPage} from "../../../../www/static/frontend/jsx/modules/account/pages/ListsPage";
// import {AddProductToListPage} from "../../../../www/static/frontend/jsx/modules/account/pages/AddProductToListPage";
// import {route} from "../../../../www/static/frontend/jsx/utils/AppData";
// import LoginForm from "../../../../www/static/frontend/jsx/modules/account/components/authorization/LoginForm";
// import RegisterForm from "../../../../www/static/frontend/jsx/modules/account/components/authorization/RegisterForm";
// import PublicProfile from "../../../../www/static/frontend/jsx/modules/account/components/public-profile/PublicProfile";
// import LoginAndSecurity
//   from "../../../../www/static/frontend/jsx/modules/account/components/login-and-security/LoginAndSecurity";
// import FormEditUserName
//   from "../../../../www/static/frontend/jsx/modules/account/components/login-and-security/FormEditUserName";
// import FormEditUserEmail
//   from "../../../../www/static/frontend/jsx/modules/account/components/login-and-security/FormEditUserEmail";
// import FormEditUserPhone
//   from "../../../../www/static/frontend/jsx/modules/account/components/login-and-security/FormEditUserPhone";
// import FormChangePassword
//   from "../../../../www/static/frontend/jsx/modules/account/components/login-and-security/FormChangePassword";
// import TSVSettings from "../../../../www/static/frontend/jsx/modules/account/components/login-and-security/TSVSettings";
// import TSVAddNewApp
//   from "../../../../www/static/frontend/jsx/modules/account/components/login-and-security/TSVAddNewApp";
// import TSVDisable from "../../../../www/static/frontend/jsx/modules/account/components/login-and-security/TSVDisable";
// import TSVChangePreferredMethod
//   from "../../../../www/static/frontend/jsx/modules/account/components/login-and-security/TSVChangePreferredMethod";
// import TSVRecovery from "../../../../www/static/frontend/jsx/modules/account/components/login-and-security/TSVRecovery";
// import PasswordAssistance
//   from "../../../../www/static/frontend/jsx/modules/account/components/password-assistance/PasswordAssistance";
// import ReviewForm from "../../../../www/static/frontend/jsx/modules/account/components/review/ReviewForm";
// import Decision from "../../../../www/static/frontend/jsx/modules/account/components/orders/Decision/Decision";
// import Decisions
//   from "../../../../www/static/frontend/jsx/modules/account/components/orders/DecisionsPreview/Decisions";
// import {OrdersPage} from "../../../../www/static/frontend/jsx/modules/account/pages/OrdersPage";
// import {OrderInfoContainerPage} from "../../../../www/static/frontend/jsx/modules/account/hoc/OrderInfoContainerPage";
// import {OrderTrackingPage} from "../../../../www/static/frontend/jsx/modules/account/pages/OrderTrackingPage";
// import {ProductsOrderedPage} from "../../../../www/static/frontend/jsx/modules/account/pages/ProductsOrderedPage";
// import {OrderAddressesPage} from "../../../../www/static/frontend/jsx/modules/account/pages/OrderAddressesPage";
// import {OrderCommunicationPage} from "../../../../www/static/frontend/jsx/modules/account/pages/OrderCommunicationPage";
// import {OrderLogPage} from "../../../../www/static/frontend/jsx/modules/account/pages/OrderLogPage";
// import {OrderActionsPage} from "../../../../www/static/frontend/jsx/modules/account/pages/OrderActionsPage";
// import {EmailPage} from "../../../../www/static/frontend/jsx/modules/account/pages/EmailPage";
// import {ChangeAddress} from "../../../../www/static/frontend/jsx/modules/account/components/orders/ChangeAddress";
// import { Addresses } from "../pages/Addresses";
// import { Transactions } from "../pages/Transactions";
// import { Wallet } from "../pages/Wallet";
// import LoginForm from "../../account/components/authorization/LoginForm";
// import RegisterForm from "../../account/components/authorization/RegisterForm";
// import { AddAddressPage } from "../pages/AddAddressPage";
// import SideBarMenu from "../components/sidebar-menu/SideBarMenu";
// import PublicProfile from "../components/public-profile/PublicProfile";
// import LoginAndSecurity from "@modules/account/components/login-and-security/LoginAndSecurity";
// import FormEditUserName from "@modules/account/components/login-and-security/FormEditUserName";
// import FormEditUserEmail from "@modules/account/components/login-and-security/FormEditUserEmail";
// import FormEditUserPhone from "@modules/account/components/login-and-security/FormEditUserPhone";
// import FormChangePassword from "@modules/account/components/login-and-security/FormChangePassword"
// import { route } from "@utils/AppData";
// import { ListsSidebarMenu } from "../components/lists/ListsSidebarMenu";
// import { ListsPage } from "../pages/ListsPage";
// import TSVSettings from "@modules/account/components/login-and-security/TSVSettings";
// import TSVAddNewApp from "@modules/account/components/login-and-security/TSVAddNewApp";
// import { EditCard } from "@modules/account/pages/EditCard";
// import { AddCard } from "@modules/account/pages/AddCard";
// import { RemoveCardPage } from "@modules/account/pages/RemoveCardPage";
// import { PageContainerHoc } from "@modules/account/hoc/PageContainerHoc";
// import { InvitationPage } from "../pages/InvitationPage";
// import TSVDisable from "@modules/account/components/login-and-security/TSVDisable";
// import TSVChangePreferredMethod from "@modules/account/components/login-and-security/TSVChangePreferredMethod";
// import TSVRecovery from "@modules/account/components/login-and-security/TSVRecovery";
// import PasswordAssistance from "@modules/account/components/password-assistance/PasswordAssistance";
// import { EditInfoInListProductPage } from "@modules/account/pages/EditInfoInListProductPage";
// import { ManageListPage } from "@modules/account/pages/ManageListPage";
// import { ShareListPage } from "@modules/account/pages/ShareListPage";
// import { DeleteListPage } from "@modules/account/pages/DeleteListPage";
// import { AddListPage } from "@modules/account/pages/AddListPage";
// import { AddIdeaPage } from "@modules/account/pages/AddIdeaPage";
// import { AddProductToListPage } from "@modules/account/pages/AddProductToListPage";
// import { MoveProductPage } from "@modules/account/pages/MoveProductPage";
// import { DashboardPage } from "@modules/account/pages/DashboardPage";
// import ReviewForm from "@modules/account/components/review/ReviewForm";
// import { DeleteProductPage } from "@modules/account/pages/DeleteProductPage";
// import { OrdersPage } from "@modules/account/pages/OrdersPage";
// import { OrderInfoContainerPage } from "@modules/account/hoc/OrderInfoContainerPage";
// import { OrderTrackingPage } from "@modules/account/pages/OrderTrackingPage";
// import { ProductsOrderedPage } from "@modules/account/pages/ProductsOrderedPage";
// import { OrderAddressesPage } from "@modules/account/pages/OrderAddressesPage";
// import { OrderCommunicationPage } from "@modules/account/pages/OrderCommunicationPage";
// import { OrderLogPage } from "@modules/account/pages/OrderLogPage";
// import { OrderActionsPage } from "@modules/account/pages/OrderActionsPage";
// import { EmailPage } from "@modules/account/pages/EmailPage";
// import { ChangeAddress } from "@modules/account/components/orders/ChangeAddress";
// import Decision from "@modules/account/components/orders/Decision/Decision";
// import Decisions from "@modules/account/components/orders/DecisionsPreview/Decisions";

export const AccountRouters = (): any => {
  const dispatch = useDispatch();
  const user = useSelector((e: StoreInterface) => e.user);

  useEffect(() => {
    dispatch(getTerritory());

    if (Store.getState().user) {
      dispatch(getAddresses(Store.getState().user.id));
    }
  }, []);

  dispatch(setBreadcrumbsAddresses(staticRoutes));

  return (
    <>
      {/*<ShadowPanel />*/}
      <Snackbar>
        <HatNavigation />
        <HatSearchLine isStatic={true} />
        {/*<MenuMobile isStatic={false} />*/}
      </Snackbar>
    </>
  );
};
