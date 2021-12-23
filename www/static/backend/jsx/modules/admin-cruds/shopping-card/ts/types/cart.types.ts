export interface CartProduct {
  name: string;
  sku: string;
  price: number;
  quantity: number;
  totalPrice: number;
  urlProduct: string;
  image: string;
}
export interface CartItem {
  id: number;
  products: CartProduct[];
}
