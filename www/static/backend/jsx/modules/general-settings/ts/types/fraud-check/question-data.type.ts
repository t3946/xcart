export interface TableDataResponse {
  value: number | string;
  f_fraud: string;
  t_fraud: string;
  template: string;
  questionId: string | number;
}
export interface TableBaseQuestion {
  questionId: number | string;
  questionCode: string;
  auto: string;
  template: string;
  weight: string | number;
  type: string;
}
