import { EmailStoreItems } from "../ts/types";

export function editCheckedItems(
  checkedItems: string[],
  prevValue: string,
  thisValue: string,
  items: EmailStoreItems[],
  multiply: boolean
): string[] {
  const isChecked = Boolean(
    checkedItems.find((item) => item === items[thisValue].item.id)
  );
  const value = Number(thisValue);
  const previouslyValue = Number(prevValue);

  if (multiply) {
    items.map((e, index) => {
      if (prevValue > thisValue) {
        if (index <= previouslyValue && index >= value) {
          checkedItems = edit(checkedItems, isChecked, e.item.id);
        }
      } else {
        if (index >= previouslyValue && index <= value) {
          checkedItems = edit(checkedItems, isChecked, e.item.id);
        }
      }
    });
    return Array.from(new Set(checkedItems));
  }
  return (checkedItems = edit(
    checkedItems,
    isChecked,
    items[thisValue].item.id
  ));
}

function edit(items: string[], checked: boolean, id: string): string[] {
  if (checked) {
    return items.filter((item) => item !== id);
  }
  return items.concat(id);
}
