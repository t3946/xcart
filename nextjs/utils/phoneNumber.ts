import { getCountryByPhoneCode } from "@utils/Countries";

export function getMaskedPhone(phone: string): string {
  const number = phone.slice(phone.length - 10, phone.length);
  return `(${number.slice(0, 3)}) ${number.slice(3, 6)}-${number.slice(6, 10)}`;
}

export function getPhoneCountryCode(phone: string, countries: any): string {
  return getCountryByPhoneCode(
    parseInt(phone.slice(0, phone.length - 10)),
    countries
  ).code;
}
