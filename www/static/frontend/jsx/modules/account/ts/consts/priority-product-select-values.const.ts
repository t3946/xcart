import { PriorityProductEnum } from "@client/modules/account/ts/consts/priority-product.enum";
import { SelectValue } from "@client/modules/account/ts/types/select-value.type";

export const priorityProductSelectValuesConst: SelectValue<
  PriorityProductEnum,
  string
>[] = [
  {
    value: PriorityProductEnum.LOWEST,
    viewValue: "Lowest",
  },
  {
    value: PriorityProductEnum.LOW,
    viewValue: "Low",
  },
  {
    value: PriorityProductEnum.MEDIUM,
    viewValue: "Medium",
  },
  {
    value: PriorityProductEnum.HIGH,
    viewValue: "High",
  },
  {
    value: PriorityProductEnum.HIGHEST,
    viewValue: "Highest",
  },
];
