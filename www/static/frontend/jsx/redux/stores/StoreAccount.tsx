import { applyMiddleware, combineReducers, createStore, Store } from "redux";
import createSagaMiddleware from "redux-saga";
import { composeWithDevTools } from "redux-devtools-extension";
import accountAddressesReducer from "../reduсers/account/AddresesReduсer";
import { accountStoreInitialValue } from "../../modules/account/ts/consts/account-store-initial-value";
import { AccountStore } from "../../modules/account/ts/types/account-store.type";
import accountRootSaga from "../sagas/account-sagas/MainSaga";
import accountSharedReducer from "../reduсers/account/SharedReduсer";
import WalletReducer from "../reduсers/account/PaymentsReducer";
import MenuReducer from "../reduсers/account/MenuReducer";
import UserReducer from "../reduсers/account/UserReduсer";
import BreadcrumbsReducer from "../reduсers/account/BreadcrumbsReducer";
import ShadowPanelReducer from "@client/jsx/redux/reduсers/account/ShadowPanelReducer";
import CountriesReducer from "@client/jsx/redux/reduсers/account/CountriesReducer";
import DepartmentsMenuReducer from "@client/jsx/redux/reduсers/account/DepartmentsMenuReducer";
import DepartmentsMenuMobileReducer from "@client/jsx/redux/reduсers/account/DepartmentsMenuMobileReducer";

const sagaMiddleware = createSagaMiddleware();

export const accountStore: Store<AccountStore> = createStore(
  combineReducers({
    addresses: accountAddressesReducer,
    main: accountSharedReducer,
    payments: WalletReducer,
    mobileMenu: MenuReducer,
    user: UserReducer,
    breadcrumbs: BreadcrumbsReducer,
    shadowPanel: ShadowPanelReducer,
    countries: CountriesReducer,
    departmentsMenu: DepartmentsMenuReducer,
    departmentsMenuMobile: DepartmentsMenuMobileReducer,
  }),
  accountStoreInitialValue,
  composeWithDevTools(applyMiddleware(sagaMiddleware))
);

sagaMiddleware.run(accountRootSaga);
