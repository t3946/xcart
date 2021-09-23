export const showShadowPanelAction = (zIndex?: number): any => ({
  type: "SHOW_SHADOW",
  zIndex,
});

export const setVisibleShadowPanelAction = (isVisible: boolean): any => ({
  type: "SET_VISIBLE_SHADOW",
  isVisible,
});

export const subscribeShadowPanelAction = (subscriber: string): any => ({
  type: "SUBSCRIBE",
  subscriber,
});

export const subscriberUpdateShadowPanelAction = (
  subscriber: string,
  isVisible: boolean
): any => ({
  type: "SUBSCRIBER_UPDATE",
  subscriber,
  isVisible,
});
