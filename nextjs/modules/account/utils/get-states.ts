import { StatesDto } from "@modules/account/ts/types/states.type";

export const getStates = (
  states: StatesDto[],
  country_id: number
): StatesDto[] => {
  return states.filter((state) => state.country_id === country_id);
};
