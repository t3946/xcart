import { StoreDto } from "@s3stores-mail/ts/types";

export const initialValues: StoreDto = {
  items: [],
  itemsCount: undefined,
  searchOptions: {
    from: "",
    to: "",
    subject: "",
    dateAfter: null,
    dateBefore: null,
    hasAttachment: false,
  },
  templateType: {
    name: "Select receiving party",
  },
  sendTemplate: {
    template_name: "Select template",
    message_body: "",
  },
  sendData: {
    date: null,
    replyText: "",
    body: "",
    files: [],
    to: [],
    subject: undefined,
  },
  checkedItems: [],
  checkedItemsOptions: {
    prevValue: 0,
  },
  user: {},
  page: 0,
  loading: false,
  moreViewed: false,
  moreFavorites: false,
  templates: [],
};
