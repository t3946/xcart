export function convertActionDataToPost(items) {
  return items.map((item) => {
    return {
      id: item,
      date: new Date(),
    };
  });
}
