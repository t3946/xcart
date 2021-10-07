import {
  ActionAddFileDto,
  ActionDeleteFileDto,
  ActionDto,
  ActionEditActions,
  ActionEditCheckedItemsDto,
  ActionEditFavorites,
  ActionEditSendDataDto,
  ActionGetPageDto,
  ActionSetSearchOptions,
  ActionSetSendTemplateDto,
  ActionSetSendTemplateTypeDto,
  ActionSetViewed,
  SearchDataDto,
  SendDataDto,
} from "@s3stores-mail/ts/types";
import { SelectItemDto } from "@s3stores-mail/ts/types/select-item.type";
import { ColorCreateLabel } from "@s3stores-mail/ts/types/label";
import { EmailDto } from "@s3stores-mail/ts/types/email.type";

export const getPage = (
  page: number,
  searchParams: SearchDataDto
): ActionGetPageDto => ({
  type: "GET_PAGE",
  page: page,
  searchParams,
});

export const setSearchOptions = (
  title = undefined
): ActionSetSearchOptions => ({
  type: "SET_SEARCH_OPTIONS",
  searchOptions: {
    title: title,
  },
});

export const setSendTemplateType = (
  item: SelectItemDto
): ActionSetSendTemplateTypeDto => ({
  type: "SET_SEND_TEMPLATE_TYPE",
  templateType: item,
});

export const setSendTemplate = (item: any): ActionSetSendTemplateDto => ({
  type: "SET_SEND_TEMPLATE",
  sendTemplate: item,
});

export const editSendData = (
  data: Date | string,
  field: string
): ActionEditSendDataDto => ({
  type: "EDIT_SEND_DATA",
  data,
  field,
});

export const editCheckedItems = (
  id: number,
  multiply: boolean
): ActionEditCheckedItemsDto => ({
  type: "EDIT_CHECKED_ITEMS",
  id: id,
  multiply: multiply,
});

export const editFavorites = (
  id: string[],
  value: boolean,
  parentMessageId?: string,
  messageId?: string
): ActionEditFavorites => ({
  type: "EDIT_FAVORITES",
  favoriteItems: id,
  value,
  parentMessageId,
  messageId,
});

export const editActions = (
  id: string[],
  parentMessageId?: string
): ActionEditActions => ({
  type: "EDIT_ACTIONS",
  actionItems: id,
  parentMessageId,
});

export const addFile = (item: File): ActionAddFileDto => ({
  type: "ADD_FILE",
  item: item,
});

export const deleteFile = (path: string): ActionDeleteFileDto => ({
  type: "DELETE_FILE",
  path: path,
});

export const resetSendData = (): ActionDto => ({
  type: "RESET_SEND_DATA",
});

export const setLoading = (): ActionDto => ({
  type: "SET_LOADING",
});

export const setViewed = (
  id: string[],
  value: boolean,
  parentMessageId?: string
): ActionSetViewed => ({
  type: "SET_VIEWED",
  emailId: id,
  value,
  parentMessageId,
});

export const getTemplates = (): ActionDto => ({
  type: "GET_TEMPLATES",
});

export const addRecipient = (value: string): any => ({
  type: "ADD_RECIPIENT",
  value,
});

export const deleteRecipient = (value: string): any => ({
  type: "DELETE_RECIPIENT",
  value,
});

export const editRecipient = (value: string, newValue: string): any => ({
  type: "EDIT_RECIPIENT",
  value,
  newValue,
});

export const editSearchOptions = (searchOptions: SearchDataDto): any => ({
  type: "EDIT_SEARCH_OPTIONS",
  searchOptions,
});

export const sendEmail = (email: SendDataDto): any => ({
  type: "SEND_EMAIL",
  email,
});

export const getEmailInfo = (id: string): any => ({
  type: "GET_EMAIL_INFO",
  id,
});
export const createLabel = (
  parentMessageId: string,
  messageId: string,
  nameLabel: string,
  color: ColorCreateLabel
): any => ({
  type: "CREATE_LABEL",
  parentMessageId,
  messageId,
  nameLabel,
  color,
});
export const addLabelEmail = (
  parentMessageId: string,
  messageId: string,
  labelId: string
): any => ({
  type: "ADD_LABEL_MAIL",
  parentMessageId,
  messageId,
  labelId,
});
export const removeLabelEmail = (
  parentMessageId: string,
  messageId: string,
  labelId: string
): any => ({
  type: "REMOVE_LABEL",
  messageId,
  labelId,
  parentMessageId,
});
export const setEmailInfo = (emailInfo: EmailDto): any => ({
  type: "SET_EMAIL_INFO",
  emailInfo,
});
export const getChildEmailList = (id: string): any => ({
  type: "GET_CHILD_LIST",
  id,
});
