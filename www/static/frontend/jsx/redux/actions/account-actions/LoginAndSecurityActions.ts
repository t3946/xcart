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

export const setAlertAction = (alert: Record<any, any>): any => ({
  type: "SET_ALERT",
  alert,
});
