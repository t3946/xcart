import { AddressItemDto } from "./address-item.type";
import { SelectValue } from "./select-value.type";
import { CardItemDto, SubmitFormDataDto } from "./wallet.type";

export interface AccountStore {
  addresses: AccountAddressesStore;
  main: AccountMainStore;
  user: any;
  payments: AccountPaymentsStore;
  mobileMenu: any;
  lists: AccountListsStore;
}

export interface AccountAddressesStore {
  addressesList: AddressItemDto[];
  loading: boolean;
  addressFormLoading?: boolean;
}

export interface AccountMainStore {
  countries: SelectValue<string, string>[];
  states: any;
  breakpoint?: any;
  isList: boolean;
}

export interface AccountListsStore {
  lists: any[];
}

export interface AccountPaymentsStore {
  cards: CardItemDto[];
  cardsLoading: boolean;
  submitFormData?: SubmitFormDataDto | null;
  submitCardFormLoading?: boolean;
  transactions: any;
}
