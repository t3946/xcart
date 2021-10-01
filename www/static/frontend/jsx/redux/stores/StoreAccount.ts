import { applyMiddleware, combineReducers, createStore, Store } from "redux";
import createSagaMiddleware from "redux-saga";
import { composeWithDevTools } from "redux-devtools-extension";
import accountAddressesReducer from "../reduсers/account/AddresesReduсer";
import accountStoreInitialValue from "../../modules/account/ts/consts/account-store-initial-value";
import { AccountStore } from "../../modules/account/ts/types/account-store.type";
import accountRootSaga from "../sagas/account-sagas/MainSaga";
import accountMainReducer from "../reduсers/account/MainReduсer";
import WalletReducer from "../reduсers/account/PaymentsReducer";
import MenuReducer from "../reduсers/account/MenuReducer";
import UserReducer from "../reduсers/account/UserReduсer";
import BreadcrumbsReducer from "../reduсers/account/BreadcrumbsReducer";
import ShadowPanelReducer from "@client/jsx/redux/reduсers/account/ShadowPanelReducer";
import CountriesReducer from "@client/jsx/redux/reduсers/account/CountriesReducer";
import ListsReducer from "@client/jsx/redux/reduсers/account/ListsReducer";
import DepartmentsMenuReducer from "@client/jsx/redux/reduсers/account/DepartmentsMenuReducer";
import DepartmentsMenuMobileReducer from "@client/jsx/redux/reduсers/account/DepartmentsMenuMobileReducer";
import DepartmentsMenuDesktopReducer from "@client/jsx/redux/reduсers/account/DepartmentsMenuDesktopReducer";
import LoginAndSecurityReducer from "@client/jsx/redux/reduсers/account/LoginAndSecurityReducer";
import MobileAlertReducer from "@client/jsx/redux/reduсers/account/MobileAlertReducer";
import CartReducer from "@client/jsx/redux/reduсers/CartReducer";
import MiniCartReducer from "@client/jsx/redux/reduсers/MiniCartReducer";
import PublicProfileReducer from "@client/jsx/redux/reduсers/account/PublicProfileReducer";
import RatingsReducer from "@client/jsx/redux/reduсers/RatingsReducer";

const sagaMiddleware = createSagaMiddleware();

export const accountStore: Store<AccountStore> = createStore(
  combineReducers({
    addresses: accountAddressesReducer,
    main: accountMainReducer,
    payments: WalletReducer,
    mobileMenu: MenuReducer,
    user: UserReducer,
    breadcrumbs: BreadcrumbsReducer,
    shadowPanel: ShadowPanelReducer,
    countries: CountriesReducer,
    lists: ListsReducer,
    departmentsMenu: DepartmentsMenuReducer,
    departmentsMenuMobile: DepartmentsMenuMobileReducer,
    departmentsMenuDesktop: DepartmentsMenuDesktopReducer,
    loginAndSecurity: LoginAndSecurityReducer,
    mobileAlert: MobileAlertReducer,
    cart: CartReducer,
    miniCart: MiniCartReducer,
    publicProfile: PublicProfileReducer,
    productsRatings: RatingsReducer,
  }),
  accountStoreInitialValue,
  composeWithDevTools(applyMiddleware(sagaMiddleware))
);

sagaMiddleware.run(accountRootSaga);
