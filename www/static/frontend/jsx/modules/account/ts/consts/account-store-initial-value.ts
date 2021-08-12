export const accountMainStoreInitialValue = {
  countries: [],
  states: [],
};

export const accountWalletStoreInitialValue = {
  cards: undefined,
  cardsLoading: false,
};

export const accountAddressesInitialValue = {
  loading: false,
  addressesList: undefined,
};

export const accountStoreInitialValue = {
  main: accountMainStoreInitialValue,
  addresses: accountAddressesInitialValue,
};

export const accountMenuInitialValue = {
  isMobileMenuVisible: false,
  isTabletMenuVisible: false,
};

// eslint-disable-next-line @typescript-eslint/ban-ts-comment
// @ts-ignore
const appData = window.appData;

export const accountUserInitialValue = appData.user || null;

export const shadowPanelInitialValue = { isVisible: false };
