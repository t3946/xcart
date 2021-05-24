export function editActionItems(emails, actionItems) {
  return emails.map((item) => {
    actionItems.map((e) => {
      if (item.item.id === e) {
        item.item.action.action = !item.item.action.action;
      }
    });
    return {
      checked: item.checked,
      item: item.item,
    };
  });
}
