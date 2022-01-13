export const getCountryByCode = (
  countryCode: string,
  countries: Record<any, any>[]
): Record<any, any> => {
  for (const country of countries) {
    if (country.code === countryCode) {
      return country;
    }
  }

  return {};
};
