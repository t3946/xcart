export const getAddresses = (userId: number): any => ({
  type: "GET_ADDRESSES",
  userId,
});

export const changeDefaultAddress = (id: number, userId: number): any => ({
  type: "CHANGE_DEFAULT_ADDRESS",
  id,
  userId,
});

export const removeAddress = (id: number): any => ({
  type: "REMOVE_ADDRESS",
  id,
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
