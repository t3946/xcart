import { UserPrivateVariantsEnum } from "@modules/account/ts/consts/user-private-variants.enum";
import { PriorityProductEnum } from "@modules/account/ts/consts/priority-product.enum";
import { ListItemTypeEnum } from "@modules/account/ts/consts/list-item-type.enum";
import { User } from "@modules/account/ts/types/user.type";
import { AccountListProductActionEnum } from "@modules/account/ts/types/account-list-product-action";
import { ListPrivateEnum } from "@modules/account/ts/consts/list-private.enum";

export interface List {
  address_id: number;
  birthday: string | null;
  cache_url: string;
  description: string | null;
  list_info: ListInfo;
  name: string;
  product_list_id: string;
  products: ListItem[];
  users: ListProductUser[];
  recipient_name: string | null;
  recipient_email: string | null;
}

export interface ListInfo {
  id: string;
  list_type: ListPrivateEnum;
  product_list_id: string;
  role: UserPrivateVariantsEnum;
  user_id: string;
}

export interface ListItem {
  comment: string;
  has: string;
  image?: string;
  list_items_id: string;
  needs: string;
  order_by: string;
  priority: PriorityProductEnum;
  product: ListProductInfo | ListIdeaInfo;
  product_id: string;
  product_list_id: string;
  product_type: ListItemTypeEnum;
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
  productcode: string;
  productid: string;
  cost_to_us: string;
  price: number;
  mult_order_quantity: string;
  min_amount: number;
  avail: number;
}

export interface ListProductUser {
  user: User;
  user_id: string;
  list_type: string;
  product_list_id: string;
  role: UserPrivateVariantsEnum;
  id: string;
}
