export const savePublicProfileAction = (payload: Record<any, any>): any => ({
  type: "ACCOUNT_SAVE_PUBLIC_PROFILE",
  payload,
});

export const setAlertAction = (alert: Record<any, any>): any => ({
  type: "ACCOUNT_SET_ALERT_PUBLIC_PROFILE",
  alert,
});
