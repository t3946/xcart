import { selectSendFirstItems } from "@s3stores-mail/ts/consts/select-send-items.const";
import { selectInfoItems } from "@s3stores-mail/ts/consts/select-info-items.const";
import { StoreDto } from "@s3stores-mail/ts/types";

export const initialValues: StoreDto = {
  items: [],
  itemsCount: undefined,
  searchOptions: {
    title: "",
  },
  templateType: selectSendFirstItems[0],
  sendTemplate: selectInfoItems[0].items[0],
  sendData: {
    date: new Date(),
    replyText: "",
    files: [],
  },
  checkedItems: [],
  checkedItemsOptions: {
    prevValue: 0,
  },
  loading: false,
};
