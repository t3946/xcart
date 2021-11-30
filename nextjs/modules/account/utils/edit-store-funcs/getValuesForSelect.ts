import { SelectValue } from "@modules/account/ts/types/select-value.type";

export function getValuesForSelect<A, T>(
  mass,
  value,
  viewValue
): SelectValue<A, T>[] {
  return mass?.map((e) => {
    return {
      value: e[value],
      viewValue: e[viewValue],
    };
  });
}
