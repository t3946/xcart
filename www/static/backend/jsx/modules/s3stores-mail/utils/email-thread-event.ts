import { EmailStoreItems } from "@s3stores-mail/ts/types";
import { EmailLabel } from "@s3stores-mail/ts/types/email.type";

export const changeThreadValueByField = (
  items: EmailStoreItems[],
  parentId: string,
  messageId: string,
  fieldName: string,
  value: any,
  byGmailMessageId?
): EmailStoreItems[] => {
  const attrSearch = byGmailMessageId ? "message_id" : "id";
  return items.map((item) => {
    if (item.item[attrSearch] === parentId) {
      if (item.item[attrSearch] === messageId) {
        item.item[fieldName] = value;
      }
      item.item.thread.map((child) => {
        if (child[attrSearch] === messageId) {
          child[fieldName] = value;
        }
        return child;
      });
    }
    return item;
  });
};
export const removeLabelById = (
  items: EmailStoreItems[],
  parentMessageId: string,
  messageId: string,
  withoutId: string
): EmailStoreItems[] => {
  return items.map((item) => {
    if (item.item.message_id === parentMessageId) {
      if (parentMessageId === messageId) {
        item.item.labels.filter((label) => label.label_id !== withoutId);
      }
      item.item.thread.map((child) => {
        if (child.message_id === messageId) {
          child.labels = child.labels.filter(
            (label) => label.label_id !== withoutId
          );
        }
        return child;
      });
    }
    return item;
  });
};
export const getThreadLabelList = (
  parentMessage: EmailStoreItems,
  messageId: string
): EmailLabel[] => {
  const thread = parentMessage.item.thread.find(
    (item) => item.message_id === messageId
  );
  return thread.labels;
};
