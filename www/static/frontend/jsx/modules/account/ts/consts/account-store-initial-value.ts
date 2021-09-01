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
  listLoading: false,
};

export const accountUserInitialValue = appData.user || null;

export const accountStoreInitialValue = {
  main: accountMainStoreInitialValue,
  addresses: accountAddressesInitialValue,
  user: accountUserInitialValue,
  payments: accountPaymentsStoreInitialValue,
  mobileMenu: accountMenuInitialValue,
  lists: accountListsInitialValue,
};

export const shadowPanelInitialValue = { isVisible: false };

export const countries = appData.countries || [];

export const departmentsMenu = appData.departmentsMenu || [];

export const departmentsMenuMobile = {
  isVisible: false,
};

export const TSV = appData?.tsv || null;
