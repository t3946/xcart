export interface TableDataResponse {
  value: number;
  f_fraud: string;
  t_fraud: string;
  template: string;
  questionId: number;
}
export interface TableBaseQuestion {
  questionId: number | string;
  questionCode: string;
  auto: string;
  template: string;
  weight: string | number;
  type: string;
  orderBy: number;
}
