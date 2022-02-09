import { getCountryByPhoneCode } from "@utils/Countries";

export function formatPhone(
  phone: string,
  addCode = false
): string | undefined {
  const onlyDigitsPhone = phone.replace(/\D/g, "");

  const number = onlyDigitsPhone.slice(
    onlyDigitsPhone.length - 10,
    onlyDigitsPhone.length
  );
  const match = number.match(/^(\d{3})(\d{3})(\d{4})$/);
  if (match) {
    const formattedNumber = `(${match[1]}) ${match[2]}-${match[3]}`;
    if (addCode) {
      const code = onlyDigitsPhone.slice(0, onlyDigitsPhone.length - 10);
      if (code) {
        return `+${code} ${formattedNumber}`;
      }
      return;
    } else {
      return formattedNumber;
    }
  }
}

export function getPhoneCountryCode(phone: string, countries: any): string {
  return getCountryByPhoneCode(
    parseInt(phone.slice(0, phone.length - 10)),
    countries
  ).code;
}
