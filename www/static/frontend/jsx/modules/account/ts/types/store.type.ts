import { AddressItemDto } from "./address-item.type";
import { SelectValue } from "./select-value.type";
import { CardItemDto, SubmitFormDataDto } from "./wallet.type";
import { VariantsEnum as AlertVariants } from "@client/modules/account/utils/alert";
import { List } from "@client/modules/account/ts/types/list.type";
import PhotoSwipe from "@client/libs/photoswipe/dist/photoswipe";
import DecisionsInterface from "@client/modules/account/ts/types/decision";

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
  breadcrumbs: Record<string, string>;
  countries: any;
  loginAndSecurity: AccountLoginAndSecurityStore;
  mobileAlert: AccountMobileAlertStore;
  cart: {
    quantity: number;
    checkoutUrl: string;
  };
  publicProfile: AccountPublicProfileStore;
  ordersStore: OrdersStore;
}

export interface AccountAddressesStore {
  addressesList: AddressItemDto[];
  loading: boolean;
  addressFormLoading?: boolean;
}

export interface AccountMainStore {
  countries: SelectValue<string, string>[];
  states: any;
  breakpoint?: string[];
  isList: boolean;
}

export interface AccountListsStore {
  lists: List[] | undefined;
  listLoading?: boolean;
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

export interface OrdersStore {
  ordersLoading: boolean;
  orders: {
    open: {
      items: any[] | null;
      selectValue: SelectValue<number, string>;
    };
    cancelled: {
      items: any[] | null;
      selectValue: SelectValue<number, string>;
    };
    completed: {
      items: any[] | null;
      selectValue: SelectValue<number, string>;
    };
  };
}

export interface AccountPublicProfileStore {
  alert: Record<any, any>;
}

export interface PhotoSwipeStore {
  items: [];
  gallery: PhotoSwipe;
  thumb: HTMLElement;
  thumbs: HTMLElement[];
  index: number;
  ownerId: string;
}

interface StoreInterface {
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
  cart: {
    quantity: number;
    checkoutUrl: string;
  };
  publicProfile: AccountPublicProfileStore;
  productsRatings: any;
  productsReviews: any;
  product: any;
  photoswipe: PhotoSwipeStore;
  ordersStore?: {
    orders: any;
    ordersLoading: any;
  };
  decisions: {
    solved: {
      pagination_offset: number;
      decisions: DecisionsInterface[];
    };
    notSolved: {
      pagination_offset: number;
      decisions: DecisionsInterface[];
    };
  };
  routes: Record<string, string>;
}

export default StoreInterface;
