import { SelectValue } from "@modules/account/ts/types/select-value.type";
import { Item } from "@modules/account/components/shared/FormSelect";

export interface ManageListFormData {
  listName: string;
  description: string;
  recipientName: string;
  email: string;
  isPurchase: boolean;
  isDefault: boolean;
  shippingAddress: SelectValue<string | null, string>;
  month: SelectValue<number, string>;
  day: SelectValue<number, number>;
}

export interface ManageListRequestData {
  name: string;
  description: string;
  recipient_name: string;
  recipient_email: string;
  birthday: number | string;
  address_id: number;
}
