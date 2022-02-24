export default interface DecisionsInterface {
  decision_id: number;
  options: Record<any, any>;
  order_id: number;
  solved: number;
  type: number;
  order_number: string;
  created: string;
  updated: string;
}
