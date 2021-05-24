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
} from "@s3stores-mail/ts/types";
import { SelectItemDto } from "@s3stores-mail/ts/types/select-item.type";

export const getPage = (page: number): ActionGetPageDto => ({
  type: "GET_PAGE",
  page: page,
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

export const setSendTemplate = (
  item: SelectItemDto
): ActionSetSendTemplateDto => ({
  type: "SET_SEND_TEMPLATE",
  sendTemplate: item,
});

export const editSendData = (
  data: Date,
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

export const editFavorites = (id: number[]): ActionEditFavorites => ({
  type: "EDIT_FAVORITES",
  favoriteItems: id,
});

export const editActions = (id: number[]): ActionEditActions => ({
  type: "EDIT_ACTIONS",
  actionItems: id,
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
