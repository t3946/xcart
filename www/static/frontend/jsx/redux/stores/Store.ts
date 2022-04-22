import "regenerator-runtime/runtime";
import axios from "axios";

import {applyMiddleware, combineReducers, createStore, Store as ReduxStore,} from "redux";
import createSagaMiddleware from "redux-saga";
import {composeWithDevTools} from "redux-devtools-extension";
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
import MobileSearchReducer from "@client/jsx/redux/reduсers/MobileSearchReducer";
import SuggestionReducer from "@client/jsx/redux/reduсers/SuggestionReducer";
import ConfigReducer from "@client/jsx/redux/reduсers/account/ConfigReducer";
import SiteReducer from "@client/jsx/redux/reduсers/account/SiteReducer";
import ProductPageReducer from "@client/jsx/redux/reduсers/ProductPageReducer";
import axiosInstance from "@client/jsx/utils/axiosInstance";
import {put} from "redux-saga/effects";

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
    lists: ListsReducer,
    searchMobile: MobileSearchReducer,
    suggestion: SuggestionReducer,
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
    config: ConfigReducer,
    site: SiteReducer,
    productPage: ProductPageReducer,
  }),
  {},
  composeWithDevTools(applyMiddleware(sagaMiddleware))
);

sagaMiddleware.run(accountRootSaga);

axios.get("/api/account/get-site-data").then(async (res) => {
  const initialState = res.data;

  if (initialState.user) {
    Store.dispatch({
      type: "USER_SET",
      user: initialState.user,
    });
  }

  Store.dispatch({
    type: "DEPARTMENTS_MENU_SET",
    departmentsMenu: initialState.departmentsMenu,
  });

  Store.dispatch({
    type: "CART_SET",
    cart: initialState.cart,
  });

  Store.dispatch({
    type: "SITE_SET",
    site: initialState.site,
  });

  Store.dispatch({
    type: "CONFIG_SET",
    config: initialState.config,
  });

  const pathname = document.location.pathname;

  // product page
  if (pathname.search(/^\/product\/\d+/) !== -1) {
    const productId = document.location.pathname.match(/^\/product\/(\d+)/)[1];
    let productInfo = null;
    let reviews = null;

    await axios.post("/api/account/get-product-info", {productId}, {
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    })
      .then((res) => {
        productInfo = res.data.product_info;
        reviews = res.data.reviews;
      });

    Store.dispatch({
      type: "PRODUCT_INFO_SET",
      productInfo,
    });

    Store.dispatch({
      type: "REVIEWS_SETTINGS_SET",
      reviews,
    });

    //load lists
    const lists: any = await axios
      .get("/api-client/user/lists/get-all")
      .then((response) => response.data);

    Store.dispatch({
      type: "SET_LISTS",
      lists,
    });
  }
});

export default Store;
