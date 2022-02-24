import { StatesDto } from "@modules/account/ts/types/states.type";

export const getStates = (
  states: StatesDto[],
  countryCode: string
): StatesDto[] => {
  return states.filter((state) => state.countryCode === countryCode);
};
