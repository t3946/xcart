export const editNameAction = (payload: Record<any, any>): any => ({
  type: "ACCOUNT_EDIT_NAME",
  payload,
});

export const editEmailAction = (payload: Record<any, any>): any => ({
  type: "ACCOUNT_EDIT_EMAIL",
  payload,
});

export const editPhoneAction = (payload: Record<any, any>): any => ({
  type: "ACCOUNT_EDIT_PHONE",
  payload,
});

export const changePasswordAction = (payload: Record<any, any>): any => ({
  type: "ACCOUNT_EDIT_PASSWORD",
  payload,
});
