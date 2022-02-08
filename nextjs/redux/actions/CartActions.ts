export const setQuantityAction = (payload: Record<any, any>): any => ({
  type: "CART_SET_QUANTITY",
  payload,
});

export const getAction = (payload: Record<any, any>): any => ({
  type: "CART_GET",
  payload,
});

export const delAction = (payload: Record<any, any>): any => ({
  type: "CART_DEL",
  payload,
});

export const setAction = (payload: Record<any, any>): any => ({
  type: "CART_SET",
  payload,
});
