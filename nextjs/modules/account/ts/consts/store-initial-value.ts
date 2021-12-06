import { ordersHeaderSelectValues } from "@modules/account/ts/consts/orders-header-select-values";
import getInitialState from "@services/Account";

const appData: any = null;

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
  lists: appData.user ? appData.user.lists : [],
  listLoading: false,
};

export const accountUserInitialValue = appData.user || null;

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

export const countries = appData.countries || [];

export const departmentsMenu = appData.departmentsMenu || {
  desktop: [],
  mobile: [],
};

export const departmentsMenuMobile = {
  isVisible: false,
};

export const departmentsMenuDesktop = {
  isVisible: false,
};

export const TSV = appData?.tsv || null;

export const cartInitialValue = appData.cart || {};

const solved = appData.decisions?.solved || [];
const notSolved = appData.decisions?.notSolved || [];

export const decisions = {
  solved: {
    pagination_offset: solved.length,
    decisions: solved,
  },
  notSolved: {
    pagination_offset: notSolved.length,
    decisions: notSolved,
  },
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
  decisions,
};

export default storeInitialValue;
