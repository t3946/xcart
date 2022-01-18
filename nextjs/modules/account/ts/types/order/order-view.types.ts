import {
  OrderAddress,
  OrderStoreItem,
} from "@modules/account/ts/types/order/orders-store.types";

export interface OrderView extends OrderStoreItem {
  client: OrderClient;
  orderId: number;
  orderNumber: string;
  address: OrderAddress;
  payment: OrderPayment;
  logs: Log[];
  purchase: OrderPurchase;
  emails: OrderEmail[];
}

export interface OrderPayment {
  date: number;
  status: string;
}

export interface Log {
  id: number;
  date: number;
  name: string;
  type: string;
  action: string;
}

export interface OrderClient {
  firstName: string;
  phone: string;
  phoneExt: string;
  email: string;
  shippingName: string;
  billingName: string;
}

export interface OrderPurchase {
  poNumber: string;
  company: string;
  managerName: string;
  managerPhoneExt: string;
  managerEmail: string;
  managerFax: string;
  managerPhone: string;
  accountsPayablePhone: string;
  accountsPayableFax: string;
  accountsPayableEmail: string;
  accountsPayableName: string;
}

export interface OrderEmail {
  body: string;
  attachment: string;
}
