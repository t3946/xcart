import { FormType } from "../consts";

export interface HelpCenterItemDto {
  menuItems: HelpSectionItemDto[];
}

export interface HelpSectionItemDto {
  id: number;
  title: string;
  icon?: string;
  activeIcon?: string;
  items: {
    route: string;
    itemContent: itemContentDto[];
  };
}

export interface itemContentDto {
  question: string;
  answer: string;
  formType: FormType;
}
