export function editCheckedInEmailItems(items: any, checkedItems: number[]) {
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
