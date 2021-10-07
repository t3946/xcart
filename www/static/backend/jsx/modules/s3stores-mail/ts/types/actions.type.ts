import { SelectItemDto } from "@s3stores-mail/ts/types/select-item.type";

export interface ActionDto {
  type: string;
}

export interface ActionGetPageDto extends ActionDto {
  page: number;
  searchParams: any;
}

export interface ActionSetSearchOptions extends ActionDto {
  searchOptions: {
    title: string;
  };
}

export interface ActionSetSendTemplateTypeDto extends ActionDto {
  templateType: SelectItemDto;
}

export interface ActionSetSendTemplateDto extends ActionDto {
  sendTemplate: SelectItemDto;
}

export interface ActionEditSendDataDto extends ActionDto {
  data: Date | string;
  field: string;
}

export interface ActionEditCheckedItemsDto extends ActionDto {
  id: number;
  multiply: boolean;
}

export interface ActionAddFileDto extends ActionDto {
  item: File;
}

export interface ActionDeleteFileDto extends ActionDto {
  path: string;
}

export interface ActionEditFavorites extends ActionDto {
  favoriteItems: string[];
  value: boolean;
  parentMessageId?: string;
  messageId?: string;
}

export interface ActionEditActions extends ActionDto {
  actionItems: string[];
  parentMessageId?: string;
}

export interface ActionSetViewed extends ActionDto {
  emailId: string[];
  value: boolean;
  parentMessageId?: string;
}
