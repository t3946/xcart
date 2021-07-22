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
