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

export const accountMobileMenuInitialValue = {
  isVisible: false,
};
