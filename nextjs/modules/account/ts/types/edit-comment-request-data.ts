import { PriorityProductEnum } from "@modules/account/ts/consts/priority-product.enum";

export interface EditCommentRequestData {
  comment: string | null;
  priority: PriorityProductEnum | null;
  needs: string | number | null;
  has: string | number | null;
}
