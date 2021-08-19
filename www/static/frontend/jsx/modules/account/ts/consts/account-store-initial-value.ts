const thisWindow: any = window;

const appData = thisWindow.appData;

export const accountMainStoreInitialValue = {
  countries: [],
  states: [],
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

export const accountUserInitialValue = appData.user || null;

export const accountStoreInitialValue = {
  main: accountMainStoreInitialValue,
  addresses: accountAddressesInitialValue,
  user: undefined,
  payments: accountPaymentsStoreInitialValue,
  mobileMenu: accountMenuInitialValue,
};

export const shadowPanelInitialValue = { isVisible: false };
