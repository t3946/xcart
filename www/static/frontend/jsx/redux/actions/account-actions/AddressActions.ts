export const getAddresses = (): any => ({
  type: "GET_ADDRESSES",
});

export const changeDefaultAddress = (id: number): any => ({
  type: "CHANGE_DEFAULT_ADDRESS",
  id,
});

export const removeAddress = (id: number): any => ({
  type: "REMOVE_ADDRESS",
  id,
});

export const addAddress = (address: any, onPendingEnd): any => ({
  type: "ADD_ADDRESS",
  address,
  onPendingEnd,
});

export const editAddress = (address: any, onPendingEnd): any => ({
  type: "EDIT_ADDRESS",
  address,
  onPendingEnd,
});
