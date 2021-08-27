export const getTerritory = (): any => ({
  type: "GET_TERRITORY",
});

export const setBreakpoint = (breakpoint): any => ({
  type: "SET_BREAKPOINT",
  breakpoint,
});

export const setIsList = (isList: boolean): any => ({
  type: "SET_IS_LIST",
  isList,
});
