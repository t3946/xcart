import { AddressItemDto } from "./address-item.type";
import { SelectValue } from "./select-value.type";
import { CardItemDto, SubmitFormDataDto } from "./wallet.type";
import { VariantsEnum as AlertVariants } from "@modules/account/utils/alert";
import { VariantsEnum as SnackbarVariants } from "@modules/account/components/shared/Snackbar";
import { List } from "@modules/account/ts/types/list.type";
import DecisionsInterface from "@modules/account/ts/types/decision";
import { OrdersStore as OrdersMainStore } from "@modules/account/ts/types/order/orders-store.types";
import { OrderView } from "@modules/account/ts/types/order/order-view.types";
import React from "react";

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
  snackbar: AccountSnackbarStore;
  departmentsMenuMobile: {
    isVisible: boolean;
  };
  departmentsMenuDesktop: {
    isVisible: boolean;
  };
  breadcrumbs: Record<any, any>[];
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

export interface AccountSnackbarStore {
  alert: {
    duration?: number;
    variant?: SnackbarVariants;
    message: string | React.ReactNode;
  } | null;
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

export interface SideBarMenuStore {
  menuItems: {
    to: string;
    active: boolean;
  }[];
}

interface StoreInterface {
  breadcrumbs: Record<any, any>[];
  routes: any;
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
  departmentsMenu: {
    mobile: [];
    desktop: [];
  };
  searchMobile: {
    isVisible: boolean;
  };
  sidebar: SideBarMenuStore;
  snackbar: AccountSnackbarStore;
  suggestion: {
    category_suggestions: any[];
    phrase_suggestions: any[];
    product_suggestions: string | null;
  } | null;
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
  orderView: OrderView;
  ordersStore: OrdersMainStore;
  decisions: {
    solved: {
      total: number;
      items: DecisionsInterface[];
    };
    notSolved: {
      total: number;
      items: DecisionsInterface[];
    };
  };
  config: Record<any, any>;
}

export default StoreInterface;
