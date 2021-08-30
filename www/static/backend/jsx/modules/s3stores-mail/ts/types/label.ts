export interface LabelContext {
  modal: { get: boolean; set: () => void };
  messageId: string;
}
export interface SelectMenuColor {
  background: boolean;
  color: boolean;
}
export interface ColorCreateLabel {
  background: string;
  color: string;
}
