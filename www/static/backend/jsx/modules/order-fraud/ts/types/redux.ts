import {
  FAAnswer,
  MainAnswer,
  PaymentAnswer,
} from "@admin/modules/order-fraud/ts/types/answer";
import { ColumnLegendData } from "@admin/modules/order-fraud/ts/types/settings";
import { AlertColor } from "@mui/material";

export interface FraudCheckStore {
  loading: boolean;
  noCheck: boolean;
  orderId: number;
  templateView: FAAnswer;
  data: FraudCheckData;
  alert: {
    state: boolean;
    message: string;
    status: AlertColor;
  };
}
export interface FraudCheckData {
  settings: {
    timeUnlocked: string;
    lock: boolean;
    locked_orders: boolean;
    template: string;
    statusList: { code: string; name: string }[];
    prefix: string;
  };
  legend: {
    full_name: ColumnLegendData[];
    address: ColumnLegendData[];
  };
  answer: {
    diagonal?: MainAnswer[];
    red_flags: MainAnswer[];
    payment?: PaymentAnswer;
    full_name: FAAnswer[];
    address: FAAnswer[];
  };
  columns: {
    fullName: any;
    address: any;
  };
  resultChange: any;
  orderInfo: {
    bareResult: number;
    overallResult: number;
    fraudStatus: {
      code: string;
      name: string;
    };
  };
  addressesLocation: AddressGeolocation[];
  attributes: AttributeRelated;
  groups: GroupRelatedItem[];
}
export interface AttributeRelated {
  b_address?: RelatedOrderItem[];
  b_firstname?: RelatedOrderItem[];
  b_company?: RelatedOrderItem[];
  s_address?: RelatedOrderItem[];
  s_firstname?: RelatedOrderItem[];
  s_company?: RelatedOrderItem[];
  email?: RelatedOrderItem[];
  phone?: RelatedOrderItem[];
  firstname?: RelatedOrderItem[];
  ip_location?: RelatedOrderItem[];
}
export interface GroupRelatedItem {
  products: { name: string; orders: RelatedOrderItem[] }[];
  dx: string;
}
export interface RelatedOrderItem {
  isFraud: boolean;
  orderId: number;
  prefix: string;
}
export interface AddressGeolocation {
  typeId: number;
  longitude: number;
  latitude: number;
  type: string;
}
