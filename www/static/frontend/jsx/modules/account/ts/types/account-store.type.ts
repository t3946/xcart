import { AddressItemDto } from "@modules/account/ts/types/address-item.type";

export interface AccountStoreDto {
  addresses: AddressItemDto[];
  loading: boolean;
}
