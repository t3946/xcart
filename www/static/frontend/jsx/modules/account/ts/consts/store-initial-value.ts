import { ordersHeaderSelectValues } from "@client/modules/account/ts/consts/orders-header-select-values";

export const accountMainStoreInitialValue = {
  countries: [],
  states: [],
  isList: false,
};

export const accountPaymentsStoreInitialValue = {
  cards: undefined,
  cardsLoading: false,
  transactions: undefined,
};

export const accountAddressesInitialValue = {
  loading: false,
  addressesList: undefined,
};

export const accountMenuInitialValue = {
  isMobileMenuVisible: false,
  isTabletMenuVisible: false,
};

export const accountListsInitialValue = {
  lists: [],
  listLoading: false,
};

export const accountUserInitialValue = null;

export const accountLoginAndSecurityValue = {
  alert: null,
};

export const accountMobileAlert = {
  alert: null,
  isVisible: false,
};

export const accountOrdersInitialValue = {
  ordersLoading: false,
  orders: {
    open: {
      items: null,
      selectValue: ordersHeaderSelectValues[0],
    },
    cancelled: {
      items: null,
      selectValue: ordersHeaderSelectValues[0],
    },
    completed: {
      items: null,
      selectValue: ordersHeaderSelectValues[0],
    },
  },
};

export const shadowPanelInitialValue = {
  isVisible: false,
};

export const departmentsMenu = {desktop: [], mobile: []};

export const departmentsMenuMobile = {
  isVisible: false,
};

export const departmentsMenuDesktop = {
  isVisible: false,
};

export const cartInitialValue = {
  quantity: 0,
  checkoutUrl: "",
};

export const miniCartInitialValue = {
  isVisible: false,
};

export const publicProfileInitialValue = {
  alert: null,
};

export const productsRatingsInitialValue = {};

export const productsReviewsInitialValue = {};

export const photoswipeInitialValue = {
  items: null,
  gallery: null,
  thumb: null,
  thumbs: null,
  index: 0,
  ownerId: null,
};

export const configInitialValue = {};
export const siteInitialValue = {
  mainMenu: [],
  templates: {},
  product_info: {},
};

const storeInitialValue = {
  main: accountMainStoreInitialValue,
  addresses: accountAddressesInitialValue,
  user: accountUserInitialValue,
  payments: accountPaymentsStoreInitialValue,
  mobileMenu: accountMenuInitialValue,
  lists: accountListsInitialValue,
  loginAndSecurity: accountLoginAndSecurityValue,
  mobileAlert: accountMobileAlert,
  cart: cartInitialValue,
  miniCart: miniCartInitialValue,
  publicProfile: publicProfileInitialValue,
  productsRatings: productsRatingsInitialValue,
  productsReviews: productsReviewsInitialValue,
  product: null,
  photoswipe: photoswipeInitialValue,
  config: configInitialValue,
  site: siteInitialValue
};

export default storeInitialValue;
