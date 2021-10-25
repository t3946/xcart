import { AnyAction } from "redux";
import { countries } from "@client/modules/account/ts/consts/store-initial-value";
import CountryDto from "@client/modules/account/ts/types/country.type";

const CountriesReducer = (
  store: CountryDto[] = countries,
  action: AnyAction
): any => {
  switch (action.type) {
    default:
      return store;
  }
};

export default CountriesReducer;
