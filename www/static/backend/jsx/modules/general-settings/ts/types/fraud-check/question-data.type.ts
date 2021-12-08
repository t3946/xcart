export interface TableDataResponse {
  value: number;
  f_fraud: string;
  t_fraud: string;
  template: string;
  questionId: number;
  questionCode: string;
}
export interface TableBaseQuestion {
  questionId: number;
  questionCode: string;
  auto: string;
  template: string;
  weight: number;
  type: string;
  orderBy: number;
}
