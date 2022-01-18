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
  manufacturer?: OrderManufacturer;
  products?: OrderProduct[];
  tracks?: OrderGroupTrack[];
  a2bStatus?: string;
  a2cStatus?: string;
  shippingGross: number;
  totalPst: number;
  totalTax: number;
  totalGross: number;
}

export interface OrderGroupTrack {
  number: string;
  link: string;
}

export interface OrderManufacturer {
  city: string;
  address: string;
  zip: string;
  country: string;
  state: string;
}

export interface OrderProduct {
  productId: number;
  image: string;
  product: string;
  price: number;
  code: string;
  amount: number;
}

export interface OrderAddress {
  shippingCity: string;
  shippingAddress: string;
  shippingZip: string;
  shippingState?: string;
  billingCity: string;
  billingZip: string;
  billingAddress: string;
}

export interface SelectDate {
  value: number | null;
  viewValue: string;
}
