import { List, ListItem } from "@client/modules/account/ts/types/list.type";
import { SelectValue } from "@client/modules/account/ts/types/select-value.type";

export interface ListProductItemProps {
  info: ListItem;
  drag: any;
  reorderProductList: (startIndex: number, endIndex: number) => void;
  index: number;
  listId: string;
  deleteItem: () => void;
  edit: boolean;
  onMoveClick: (value: SelectValue<string, string>) => void;
  listInfo: List;
}
