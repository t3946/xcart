export const showShadowPanelAction = (zIndex?: number): any => ({
  type: "SHOW_SHADOW",
  zIndex,
});

export const hideShadowPanelAction = (): any => ({
  type: "HIDE_SHADOW",
});
