import { EmailDto, EmailLabel } from "./email.type";
import CountryDto from "@client/modules/account/ts/types/country.type";

export interface StoreDto {
  labelsList: EmailLabel[];
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
  breadcrumbs: Record<string, string>;
  shadowPanel: {
    isVisible: boolean;
    zIndex?: number;
  };
  countries: CountryDto[];
  departmentsMenu: {
    mobile: Record<any, any>[];
    desktop: Record<any, any>[];
  };
  departmentsMenuMobile: {
    isVisible: boolean;
  };
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
  threadId: string | null;
}

export interface SearchDataDto {
  from: string;
  to: string;
  subject: string;
  dateAfter: Date | string;
  dateBefore: Date | string;
  hasAttachment: boolean;
  distributorId: string | undefined;
  label: string[];
}
