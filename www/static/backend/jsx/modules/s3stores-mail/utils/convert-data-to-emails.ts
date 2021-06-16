import { EmailDto } from "../ts/types/email.type";
import { EmailStoreItems } from "../ts/types";

export function convertDataToEmails(items: EmailDto[]): EmailStoreItems[] {
  return items.map((item) => {
    return {
      item,
      checked: false,
    };
  });
}
