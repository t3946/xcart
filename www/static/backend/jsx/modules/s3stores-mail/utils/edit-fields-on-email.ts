import {
  editObjectField,
  getFieldValue,
} from "@s3stores-mail/utils/editObjectField";

export function editFieldsOnEmail(emails, favoriteItems, field) {
  const value = !isCheckedItemsTrue(emails, favoriteItems, field);
  return emails.map((item, index) => {
    favoriteItems.map((e) => {
      if (item.item.id === e) {
        item = editObjectField(emails[index], field, value);
      }
    });
    return { item: item.item, checked: item.checked || false };
  });
}

export function isCheckedItemsTrue(emails, favoriteItems, fieldName): boolean {
  let favoriteCount = 0;

  emails.map((item) => {
    favoriteItems.map((e) => {
      if (item.item.id === e && getFieldValue(item.item, fieldName)) {
        favoriteCount++;
        console.log(getFieldValue(item.item, fieldName));
      }
    });
  });

  return favoriteCount >= favoriteItems.length - favoriteCount;
}
