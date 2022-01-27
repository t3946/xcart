import { UserPrivateVariantsEnum } from "@modules/account/ts/consts/user-private-variants.enum";
import { PriorityProductEnum } from "@modules/account/ts/consts/priority-product.enum";
import { ListItemTypeEnum } from "@modules/account/ts/consts/list-item-type.enum";
import { User } from "@modules/account/ts/types/user.type";
import { AccountListProductActionEnum } from "@modules/account/ts/types/account-list-product-action";
import { ListPrivateEnum } from "@modules/account/ts/consts/list-private.enum";

export interface List {
  listType: ListPrivateEnum;
  role: UserPrivateVariantsEnum;
  listId: number;
  addressId: number;
  birthday: string | number;
  cacheUrl: string;
  description: string | null;
  name: string;
  productListId: number;
  products: ListItem[];
  source: ListSource;
  users: ListProductUser[];
  recipientName: string | null;
  recipientEmail: string | null;
}
export enum ListSource {
  Default = "default",
  Simple = "simple",
}

export interface ListItem {
  comment: string;
  has: number | string;
  image?: string;
  list_items_id: string;
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
