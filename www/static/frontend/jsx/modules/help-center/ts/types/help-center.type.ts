import { FormType } from "../consts";

export interface HelpCenterItemDto {
  menuItems: HelpSectionItemDto[];
}

export interface HelpSectionItemDto {
  menu_id: number;
  title: string;
  icon?: string;
  active_icon?: string;
  items: itemContentDto[];
}

export interface itemContentDto {
  question: string;
  answer: string;
  form_type: FormType;
}
