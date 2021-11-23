import React from "react";

export interface IListTablePrice {
  arTable: [];
  select: { get: any; set: any };
  checked: { get: any; set: any };
  activeField: {
    get: any;
    set: (event: React.ChangeEvent<HTMLInputElement>) => void;
  };
}
export interface ITablePrice extends IListTablePrice {
  indexTable: number;
}
export interface FilesInfo {
  files: FileItem[];
  folderId: string;
}
export interface FileItem {
  id: string;
  name: string;
}
export interface Site {
  storefrontid: number;
  domain: string;
}
export interface ResponsePricesSettings {
  column: any;
  for_sale_value: any;
  sites: Site[];
}
