import { SelectItemDto } from "@s3stores-mail/ts/types/select-item.type";

export interface StoreDto {
  items: any[];
  itemsCount: number;
  searchOptions: {
    title: string;
  };
  templateType: SelectItemDto;
  sendTemplate: SelectItemDto;
  sendData: SendDataDto | { [p: string]: any };
  checkedItems: number[];
  loading: boolean;
  checkedItemsOptions: {
    prevValue: number;
  };
  page: number;
  moreFavorites: boolean;
  moreViewed: boolean;
}

export interface checkedValueDto {
  id: number;
  index: number;
}

export interface SendDataDto {
  date: Date;
  replyText: string;
  files: File[];
}
