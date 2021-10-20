import React from "react";

export interface IListTablePrice {
  arTable: [];
  select: { get: any; set: any };
  checked: { get: any; set: any };
  activeField: {
    get: any;
    set: (event: React.ChangeEvent<HTMLInputElement>) => void;
  };
  needSend: {
    get: boolean;
    set: (newState: boolean) => void;
  };
}
export interface ITablePrice extends IListTablePrice {
  indexTable: number;
}
