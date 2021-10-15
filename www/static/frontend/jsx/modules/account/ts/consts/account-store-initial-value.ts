import { ordersHeaderSelectValues } from "@client/modules/account/ts/consts/orders-header-select-values";

const thisWindow: any = window;

const appData = thisWindow.appData;

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
  lists: undefined,
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

export const departmentsMenu = appData.departmentsMenu || [];

export const departmentsMenuMobile = {
  isVisible: false,
};

export const departmentsMenuDesktop = {
  isVisible: false,
};

export const TSV = appData?.tsv || null;

export const cartInitialValue = appData.cart;

export const miniCartInitialValue = {
  isVisible: false,
};

export const publicProfileInitialValue = {
  alert: null,
};

const accountStoreInitialValue = {
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
};

export default accountStoreInitialValue;
