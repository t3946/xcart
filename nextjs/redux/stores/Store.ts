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
import StoreInterface from "@modules/account/ts/types/store.type";
import accountRootSaga from "../sagas/account/MainSaga";

//reducers
import accountAddressesReducer from "../reduсers/account/AddresesReduсer";
import accountMainReducer from "../reduсers/account/MainReduсer";
import WalletReducer from "../reduсers/account/PaymentsReducer";
import MenuReducer from "../reduсers/account/MenuReducer";
import UserReducer from "../reduсers/account/UserReduсer";
import BreadcrumbsReducer from "../reduсers/account/BreadcrumbsReducer";
import ShadowPanelReducer from "@redux/reduсers/account/ShadowPanelReducer";
import CountriesReducer from "@redux/reduсers/account/CountriesReducer";
import ListsReducer from "@redux/reduсers/account/ListsReducer";
import DepartmentsMenuReducer from "@redux/reduсers/account/DepartmentsMenuReducer";
import DepartmentsMenuMobileReducer from "@redux/reduсers/account/DepartmentsMenuMobileReducer";
import DepartmentsMenuDesktopReducer from "@redux/reduсers/account/DepartmentsMenuDesktopReducer";
import LoginAndSecurityReducer from "@redux/reduсers/account/LoginAndSecurityReducer";
import MobileAlertReducer from "@redux/reduсers/account/MobileAlertReducer";
import CartReducer from "@redux/reduсers/CartReducer";
import MiniCartReducer from "@redux/reduсers/MiniCartReducer";
import PublicProfileReducer from "@redux/reduсers/account/PublicProfileReducer";
import OrdersReducer from "@redux/reduсers/account/OrdersReducer";
import RatingsReducer from "@redux/reduсers/RatingsReducer";
import ReviewsReducer from "@redux/reduсers/ReviewsReducer";
import ProductReducer from "@redux/reduсers/ProductReducer";
import PhotoSwipeReducer from "@redux/reduсers/PhotoSwipeReducer";
import DecisionsReducer from "@redux/reduсers/account/DecisionsReducer";

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
    decisions: DecisionsReducer,
    photoswipe: PhotoSwipeReducer,
  }),
  storeInitialValue,
  composeWithDevTools(applyMiddleware(sagaMiddleware))
);

sagaMiddleware.run(accountRootSaga);

export default Store;
