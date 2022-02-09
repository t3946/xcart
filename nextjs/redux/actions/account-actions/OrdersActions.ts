export const getOrders = (ordersType: string): any => ({
  type: "GET_ORDERS",
  ordersType,
});

export const setOrders = (orders: any[], orderType: string): any => ({
  type: "SET_ORDERS",
  orders,
  orderType,
});

export const changeTimeGap = (newValue: OrdersSelectDate): any => ({
  type: "CHANGE_TIME_GAP",
  newValue,
});

export const sendEmail = (email: any, onSend: () => void): any => ({
  type: "SEND_EMAIL",
  email,
  onSend,
});
export const setOrderView = (orderId: number) => ({
  type: "FETCH_ORDER_VIEW",
  orderId,
});

export const openRMARequest = (action: Record<any, any>) => ({
  type: "OPEN_RMA_REQUEST",
  ...action,
});

export const getInvoicePdf = (action: Record<any, any>) => ({
  type: "GET_INVOICE_PDF",
  ...action,
});

export const editShippingAddress = (action: Record<any, any>) => ({
  type: "EDIT_SHIPPING_ADDRESS",
  ...action,
});
