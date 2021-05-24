export function editFavoriteItems(emails, favoriteItems) {
  return emails.map((item) => {
    favoriteItems.map((e) => {
      if (item.item.id === e) {
        item.item.favorite = !item.item.favorite;
      }
    });
    return {
      checked: item.checked,
      item: item.item,
    };
  });
}
