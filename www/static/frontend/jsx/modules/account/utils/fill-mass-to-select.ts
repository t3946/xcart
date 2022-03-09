import { SelectValue } from "../ts/types/select-value.type";

export function fillMassToSelect(
  start: number,
  end: number
): SelectValue<string, string>[] {
  const result = [];

  while (start <= end) {
    let num: string | number = start;
    if (start < 10) {
      num = "0" + start;
    }
    result.push({ value: num, viewValue: num });
    start++;
  }

  return result;
}
