export interface OrdersStore {
  loading: boolean;
  orders: OrderStoreItem[];
  selectDate: SelectDate;
}

export interface OrderStoreItem {
  orderNumber: string;
  date: number;
  orderId: number;
  type: string;
  total: number;
  groups: OrderGroup[];
  address: OrderAddress;
}

export interface OrderGroup {
  manufacturer: OrderManufacturer;
  products: OrderProduct[];
}

export interface OrderManufacturer {
  m_zip: string;
  m_city: string;
  m_address: string;
}

export interface OrderProduct {
  image: string;
  product: string;
  code: string;
  amount: number;
}

export interface OrderAddress {
  shippingCity: string;
  shippingAddress: string;
  shippingZip: string;
  shippingState?: string;
}

export interface SelectDate {
  value: number | null;
  viewValue: string;
}
