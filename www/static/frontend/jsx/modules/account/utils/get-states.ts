export const getStates = (states, countryCode) => {
  return states.filter((state) => state.countryCode === countryCode);
};
