import { List, ListItem } from "@modules/account/ts/types/list.type";
import { SelectValue } from "@modules/account/ts/types/select-value.type";

export interface ListProductItemProps {
  productItem: ListItem;
  drag: any;
  reorderProductList: (startIndex: number, endIndex: number) => void;
  index: number;
  deleteItem: () => void;
  edit: boolean;
}
