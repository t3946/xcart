import { OrderAddress } from "@modules/account/ts/types/order/orders-store.types";

export interface OrderView {
  orderId: number;
  orderNumber: string;
  groups: OrderViewGroup[];
  address: OrderAddress;
}
export interface OrderViewGroup {
  tracks: OrderGroupTrack[];
}
export interface OrderGroupTrack {
  number: string;
  link: string;
}
