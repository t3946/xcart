export const getAddresses = (userId: number): any => ({
  type: "GET_ADDRESSES",
  userId,
});

export const changeDefaultAddress = (
  id: number,
  userId: number,
  callback: () => void
): any => ({
  type: "CHANGE_DEFAULT_ADDRESS",
  id,
  userId,
  callback,
});

export const removeAddress = (id: number, callback: () => void): any => ({
  type: "REMOVE_ADDRESS",
  id,
  callback,
});

export const addAddress = (address: any, onPendingEnd: any): any => ({
  type: "ADD_ADDRESS",
  address,
  onPendingEnd,
});

export const editAddress = (address: any, onPendingEnd: any): any => ({
  type: "EDIT_ADDRESS",
  address,
  onPendingEnd,
});
