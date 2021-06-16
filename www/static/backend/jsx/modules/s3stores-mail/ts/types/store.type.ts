import { EmailDto } from "./email.type";

export interface StoreDto {
  items: EmailStoreItems[];
  itemsCount: number;
  searchOptions: SearchDataDto;
  templateType: any;
  sendTemplate: any;
  sendData: SendDataDto | { [p: string]: any };
  checkedItems: string[];
  loading: boolean;
  checkedItemsOptions: {
    prevValue: string;
  };
  emailInfo: EmailDto | null;
  page: number;
  moreFavorites: boolean;
  moreViewed: boolean;
  templates?: any;
  user: any;
}

export interface EmailStoreItems {
  item: EmailDto;
  checked: boolean;
}

export interface CheckedValueDto {
  id: number;
  index: number;
}

export interface SendDataDto {
  to: any[];
  date: Date | null;
  subject: string;
  body: string;
  replyText: string;
  files: File[];
}

export interface SearchDataDto {
  from: string;
  to: string;
  subject: string;
  dateAfter: Date | string;
  dateBefore: Date | string;
  hasAttachment: boolean;
  distributorId: string | undefined;
}
