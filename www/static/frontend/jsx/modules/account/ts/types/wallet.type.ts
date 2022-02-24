import { AddressItemDto } from "./address-item.type";

export interface SubmitFormDataDto {
  address?: AddressItemDto | { address_id: number };
  card?: any;
  user?: number;
}

export interface CardItemDto {
  credit_card_id: number;
  name: string;
  address: AddressItemDto;
  address_id: number;
  is_default: boolean;
  card_number: string;
  card_type: string;
  expires: string;
  user_id: number;
}
