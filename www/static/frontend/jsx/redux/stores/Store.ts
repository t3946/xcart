import "regenerator-runtime/runtime";

import {
  applyMiddleware,
  combineReducers,
  createStore,
  Store as ReduxStore,
} from "redux";
import createSagaMiddleware from "redux-saga";
import { composeWithDevTools } from "redux-devtools-extension";
import storeInitialValue from "../../modules/account/ts/consts/store-initial-value";
import StoreInterface from "@client/jsx/modules/account/ts/types/store.type";
import accountRootSaga from "../sagas/account/MainSaga";

//reducers
import accountAddressesReducer from "../reduсers/account/AddresesReduсer";
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
import OrdersReducer from "@client/jsx/redux/reduсers/account/OrdersReducer";
import RatingsReducer from "@client/jsx/redux/reduсers/RatingsReducer";
import ReviewsReducer from "@client/jsx/redux/reduсers/ReviewsReducer";
import ProductReducer from "@client/jsx/redux/reduсers/ProductReducer";
import PhotoSwipeReducer from "@client/jsx/redux/reduсers/PhotoSwipeReducer";
import DecisionsReducer from "@client/jsx/redux/reduсers/account/DecisionsReducer";

const sagaMiddleware = createSagaMiddleware();

const Store: ReduxStore<StoreInterface> = createStore(
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
    ordersStore: OrdersReducer,
    productsRatings: RatingsReducer,
    productsReviews: ReviewsReducer,
    product: ProductReducer,
    photoswipe: PhotoSwipeReducer,
    decisions: DecisionsReducer,
  }),
  storeInitialValue,
  composeWithDevTools(applyMiddleware(sagaMiddleware))
);

sagaMiddleware.run(accountRootSaga);

export default Store;
