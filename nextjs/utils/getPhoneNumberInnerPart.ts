import { getCountryByCode } from "@utils/Countries";
/**
 * Get phone number without country code prefix
 */

const getPhoneNumberInnerPart = function (
  countryCode: string,
  phoneNumber: string,
  countries: Record<any, any>[]
) {
  const phoneCountryCodePrefix =
    "+" + getCountryByCode(countryCode, countries).phone_code;
  return phoneNumber.replace(phoneCountryCodePrefix, "");
};

export default getPhoneNumberInnerPart;
