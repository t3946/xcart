import { editObjectField } from "@s3stores-mail/utils/editObjectField";
import { EmailActionDto, EmailDto } from "../ts/types/email.type";
import { EmailStoreItems } from "../ts/types";

export function editFieldsOnEmail<T>(
  emails: EmailStoreItems[],
  favoriteItems: string[],
  field: string,
  value: T
): EmailStoreItems[] {
  return emails.map((item) => {
    favoriteItems.map((e) => {
      if (item.item.id === e) {
        item = editObjectField(item, field, value);
      }
    });
    return { item: item.item, checked: item.checked || false };
  });
}

export function isFavoriteItemsTrue(
  emails: EmailStoreItems[],
  checkedItems: string[]
): boolean {
  let favoriteCount = 0;

  emails.map((item) => {
    checkedItems.map((e) => {
      if (item.item.id === e && item.item.favorite) {
        favoriteCount++;
      }
    });
  });
  return getNecessaryValue(favoriteCount, checkedItems);
}
export function isFavoriteThreadTrue(
  emailList: EmailDto[],
  message_id: string
): boolean {
  const email = emailList.find((email) => email.id === message_id);
  return email.favorite;
}

export function isViewedItemsTrue(
  emails: EmailStoreItems[],
  checkedItems: string[]
): boolean {
  let viewedCount = 0;

  emails.map((item) => {
    checkedItems.map((e) => {
      if (item.item.id === e && item.item.viewed) {
        viewedCount++;
      }
    });
  });
  if (viewedCount === 0) {
    return true;
  }

  if (viewedCount === checkedItems.length) {
    return false;
  }
  return viewedCount > 0;
}

export function isActionItemTrue(
  emails: EmailStoreItems[],
  checkedItems: string[],
  user: any
): EmailActionDto {
  let actionCount = 0;

  emails.map((item) => {
    checkedItems.map((e) => {
      if (item.item.id === e && item.item.action.action) {
        actionCount++;
      }
    });
  });
  if (actionCount === checkedItems.length) {
    return {
      action: false,
    };
  }
  return {
    action: true,
    name: user.login,
    date: new Date(),
  };
}

export function getNecessaryValue(
  itemCount: number,
  checkedItems: string[]
): boolean {
  if (itemCount === 0) {
    return true;
  }

  if (itemCount === checkedItems.length) {
    return false;
  }
  return itemCount >= checkedItems.length - itemCount;
}
