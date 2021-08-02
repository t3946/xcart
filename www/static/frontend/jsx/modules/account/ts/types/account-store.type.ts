import { AddressItemDto } from "@modules/account/ts/types/address-item.type";
import { SelectValueDto } from "@modules/account/ts/types/select-value.type";

export interface AccountStoreDto {
  addresses: AccountAddressesStoreDto;
  main: AccountMainStoreDto;
}

export interface AccountAddressesStoreDto {
  addressesList: AddressItemDto[];
  loading: boolean;
  addressFormLoading?: boolean;
}

export interface AccountMainStoreDto {
  countries: SelectValueDto<string, string>[];
  states: any;
}

export interface AccountWalletStoreDto {
  cards: any[];
  cardsLoading: boolean;
  submitFormData?: any;
}
