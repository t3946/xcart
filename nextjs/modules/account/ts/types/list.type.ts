import { UserPrivateVariantsEnum } from "@modules/account/ts/consts/user-private-variants.enum";
import { PriorityProductEnum } from "@modules/account/ts/consts/priority-product.enum";
import { ListItemTypeEnum } from "@modules/account/ts/consts/list-item-type.enum";
import { User } from "@modules/account/ts/types/user.type";
import { AccountListProductActionEnum } from "@modules/account/ts/types/account-list-product-action";
import { ListPrivateEnum } from "@modules/account/ts/consts/list-private.enum";

export interface List {
  list_type: ListPrivateEnum;
  role: UserPrivateVariantsEnum;
  list_id: number;
  address_id: number;
  birthday: string | number;
  cache_url: string;
  description: string | null;
  name: string;
  product_list_id: number;
  items: ListItem[];
  source: ListSource;
  users: ListProductUser[];
  recipient_name: string | null;
  recipient_email: string | null;
  product_type: string;
}
export enum ListSource {
  Default = "default",
  Simple = "simple",
}

export interface ListItem {
  list_idea_id: number;
  comment: string;
  has: number | string;
  image?: string;
  list_item_id: number;
  needs: string | number;
  orderBy: string;
  priority: PriorityProductEnum;
  product: ListProductInfo | ListIdeaInfo;
  productId: number;
  product_list_id: string;
  productType: ListItemTypeEnum;
  typeAction?: ListItemAction;
  add_date: string;
}

export interface ListItemAction {
  type: AccountListProductActionEnum;
  productName?: string;
  toListId?: string;
  listName?: string;
}

export interface ListIdeaInfo {
  product_id: string;
  name: string;
}

export interface ListProductInfo {
  product: string;
  code: string;
  productId: number;
  costToUs: string;
  price: number;
  multOrderQuantity: string;
  minAmount: number;
  avail: boolean;
}

export interface ListProductUser {
  user: User;
  userId: string;
  listType: string;
  role: UserPrivateVariantsEnum;
}
