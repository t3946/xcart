import { AnyAction } from "redux";
import CountryDto from "@modules/account/ts/types/country.type";

const CountriesReducer = (
  store: CountryDto[] = [],
  action: AnyAction
): any => {
  switch (action.type) {
    default:
      return store;
  }
};

export default CountriesReducer;
