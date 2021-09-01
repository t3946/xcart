import { EmailStoreItems } from "../ts/types";
import { EmailDto } from "../ts/types/email.type";

export function editCheckedInEmailItems(
  items: EmailDto[] | EmailStoreItems[],
  checkedItems: string[]
): EmailStoreItems[] {
  return items.map((e) => {
    const email = e.item || e;
    let checked = false;
    checkedItems.map((id) => {
      if (email.id === id) checked = true;
    });
    return {
      item: email,
      checked,
    };
  });
}
