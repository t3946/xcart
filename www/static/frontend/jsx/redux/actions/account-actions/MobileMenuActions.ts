export const setMobileAlertAction = (alert: Record<any, any>): any => ({
  type: "SET_MOBILE_ALERT",
  alert,
});

export const setIsVisibleAction = (isVisible: boolean): any => ({
  type: "SET_IS_VISIBLE_ALERT",
  isVisible,
});
