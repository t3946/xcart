import CountryDto from "@client/modules/account/ts/types/country.type";

export const getCountryByCode = (
  countryCode: string,
  countries: CountryDto[]
) => {
  for (const country of countries) {
    if (country.code === countryCode) {
      return country;
    }
  }

  return null;
};
