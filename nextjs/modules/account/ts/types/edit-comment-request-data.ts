import { PriorityProductEnum } from "@modules/account/ts/consts/priority-product.enum";

export interface EditCommentRequestData {
  comment: string;
  priority: PriorityProductEnum;
  needs: number;
  has: number;
}
