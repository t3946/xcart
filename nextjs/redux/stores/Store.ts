import "regenerator-runtime/runtime";

import { applyMiddleware, combineReducers, createStore } from "redux";
import createSagaMiddleware from "redux-saga";
import { composeWithDevTools } from "redux-devtools-extension";
import accountRootSaga from "../sagas/account/MainSaga";

import UserReducer from "../reducers/account/UserReducer";
import accountAddressesReducer from "../reducers/account/AddressesReducer";
import accountMainReducer from "../reducers/account/MainReducer";
// import WalletReducer from "../reducers/account/PaymentsReducer";
import MenuReducer from "../reducers/account/MenuReducer";
import RoutesReducer from "../reducers/RoutesReducer";
import BreadcrumbsReducer from "@redux/reducers/account/BreadcrumbsReducer";
import SnackbarReducer from "@redux/reducers/account/SnackbarReducer";
import ShadowPanelReducer from "@redux/reducers/account/ShadowPanelReducer";
import CountriesReducer from "@redux/reducers/account/CountriesReducer";
import ListsReducer from "@redux/reducers/account/ListsReducer";
import DepartmentsMenuReducer from "@redux/reducers/account/DepartmentsMenuReducer";
import DepartmentsMenuMobileReducer from "@redux/reducers/account/DepartmentsMenuMobileReducer";
import DepartmentsMenuDesktopReducer from "@redux/reducers/account/DepartmentsMenuDesktopReducer";
import MobileSearchReducer from "@redux/reducers/account/MobileSearchReducer";
import SuggestionReducer from "@redux/reducers/account/SuggestionReducer";
import LoginAndSecurityReducer from "@redux/reducers/account/LoginAndSecurityReducer";
import MobileAlertReducer from "@redux/reducers/account/MobileAlertReducer";
import CartReducer from "@redux/reducers/CartReducer";
// import MiniCartReducer from "@redux/reducers/MiniCartReducer";
import PublicProfileReducer from "@redux/reducers/account/PublicProfileReducer";
import OrdersReducer from "@redux/reducers/account/OrdersReducer";
import SideBarMenuReducer from "@redux/reducers/account/SideBarMenuReducer";
// import RatingsReducer from "@redux/reducers/RatingsReducer";
// import ReviewsReducer from "@redux/reducers/ReviewsReducer";
// import ProductReducer from "@redux/reducers/ProductReducer";
// import PhotoSwipeReducer from "@redux/reducers/PhotoSwipeReducer";
import DecisionsReducer from "@redux/reducers/account/DecisionsReducer";
import ConfigReducer from "@redux/reducers/ConfigReducer";
import MainMenuReducer from "@redux/reducers/MainMenuReducer";

import { staticRoutes } from "@modules/account/ts/consts/breadcrumbs";
import OrderViewReducer from "@redux/reducers/account/OrderViewReducer";

const sagaMiddleware = createSagaMiddleware();
let clientPreloadedState;

if (process.browser) {
  // eslint-disable-next-line @typescript-eslint/ban-ts-comment
  // @ts-ignore
  clientPreloadedState = window.__PRELOADED_STATE__;
  clientPreloadedState.breadcrumbs = staticRoutes;
  console.log("__PRELOADED_STATE__", clientPreloadedState);
  // eslint-disable-next-line @typescript-eslint/ban-ts-comment
  // @ts-ignore
  delete window.__PRELOADED_STATE__;
}

const reducers = combineReducers({
  user: UserReducer,
  routes: RoutesReducer,
  addresses: accountAddressesReducer,
  main: accountMainReducer,
  // payments: WalletReducer,
  mobileMenu: MenuReducer,
  breadcrumbs: BreadcrumbsReducer,
  shadowPanel: ShadowPanelReducer,
  countries: CountriesReducer,
  lists: ListsReducer,
  departmentsMenu: DepartmentsMenuReducer,
  departmentsMenuMobile: DepartmentsMenuMobileReducer,
  departmentsMenuDesktop: DepartmentsMenuDesktopReducer,
  searchMobile: MobileSearchReducer,
  snackbar: SnackbarReducer,
  suggestion: SuggestionReducer,
  loginAndSecurity: LoginAndSecurityReducer,
  mobileAlert: MobileAlertReducer,
  cart: CartReducer,
  sidebar: SideBarMenuReducer,
  // miniCart: MiniCartReducer,
  publicProfile: PublicProfileReducer,
  ordersStore: OrdersReducer,
  orderView: OrderViewReducer,
  // productsRatings: RatingsReducer,
  // productsReviews: ReviewsReducer,
  // product: ProductReducer,
  decisions: DecisionsReducer,
  // photoswipe: PhotoSwipeReducer,
  mainMenu: MainMenuReducer,
  config: ConfigReducer,
});

const store = createStore(
  reducers,
  clientPreloadedState,
  composeWithDevTools(applyMiddleware(sagaMiddleware))
);

sagaMiddleware.run(accountRootSaga);

export default store;

export const getServerStore = function (serverPreloadedState: any) {
  serverPreloadedState.breadcrumbs = staticRoutes;

  return createStore(
    reducers,
    serverPreloadedState,
    composeWithDevTools(applyMiddleware(sagaMiddleware))
  );
};
