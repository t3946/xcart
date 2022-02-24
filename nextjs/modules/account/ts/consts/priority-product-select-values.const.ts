import { PriorityProductEnum } from "@modules/account/ts/consts/priority-product.enum";
import { SelectValue } from "@modules/account/ts/types/select-value.type";

export const priorityProductSelectValuesConst: SelectValue<
  PriorityProductEnum,
  string
>[] = [
  {
    value: PriorityProductEnum.LOWEST,
    label: "Lowest",
  },
  {
    value: PriorityProductEnum.LOW,
    label: "Low",
  },
  {
    value: PriorityProductEnum.MEDIUM,
    label: "Medium",
  },
  {
    value: PriorityProductEnum.HIGH,
    label: "High",
  },
  {
    value: PriorityProductEnum.HIGHEST,
    label: "Highest",
  },
];
