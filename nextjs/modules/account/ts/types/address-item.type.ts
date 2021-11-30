import { AddressTypeEnum } from "@modules/account/ts/consts/address-type.const";

export interface AddressItemDto {
  address_id?: number;
  full_name: string;
  country: string;
  phone_number: string;
  street: string;
  detailed: string;
  city: string;
  state: string;
  zip: string;
  default: boolean;
  delivery_type: string;
  address_type: AddressTypeEnum;
}
