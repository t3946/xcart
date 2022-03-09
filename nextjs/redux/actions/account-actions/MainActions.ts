export const getTerritory = (): any => ({
  type: "GET_TERRITORY",
});

export const setBreakpoint = (breakpoint: string[]): any => ({
  type: "SET_BREAKPOINT",
  breakpoint,
});
