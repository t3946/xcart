import { AddressItemDto } from "./address-item.type";
import { SelectValue } from "./select-value.type";
import { CardItemDto, SubmitFormDataDto } from "./wallet.type";
import { VariantsEnum as AlertVariants } from "@client/modules/account/utils/alert";

export interface AccountStore {
  addresses: AccountAddressesStore;
  main: AccountMainStore;
  user: any;
  payments: AccountPaymentsStore;
  mobileMenu: any;
  lists: AccountListsStore;
  shadowPanel: {
    isVisible: boolean;
    zIndex: number;
    subscribers: Record<string, boolean>;
  };
  departmentsMenuMobile: {
    isVisible: boolean;
  };
  departmentsMenuDesktop: {
    isVisible: boolean;
  };
  countries: any;
  loginAndSecurity: AccountLoginAndSecurityStore;
  mobileAlert: AccountMobileAlertStore;
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
  listLoading: boolean;
}

export interface AccountPaymentsStore {
  cards: CardItemDto[];
  cardsLoading: boolean;
  submitFormData?: SubmitFormDataDto | null;
  submitCardFormLoading?: boolean;
  transactions: any;
}

export interface AccountLoginAndSecurityStore {
  alert: {
    variant: AlertVariants;
    message: string;
  };
}

export interface AccountMobileAlertStore {
  isVisible: boolean;
  alert: {
    variant: AlertVariants;
    message: string;
  };
}
