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

export const addAddress = (
  address: any,
  onPendingEnd,
  userId: number
): any => ({
  type: "ADD_ADDRESS",
  address,
  onPendingEnd,
  userId,
});

export const editAddress = (address: any, onPendingEnd): any => ({
  type: "EDIT_ADDRESS",
  address,
  onPendingEnd,
});
