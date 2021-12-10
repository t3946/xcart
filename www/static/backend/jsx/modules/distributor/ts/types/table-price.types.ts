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
  logs: UploadLog[];
}
export interface FileItem {
  id: string;
  name: string;
  dateCreate: number;
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
export interface UploadLog {
  uploadId: number;
  date: string;
  count: number;
  userUpload: string;
  status: string;
  name: string;
}
