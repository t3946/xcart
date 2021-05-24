export function convertDataToEmails(items: any): any {
  return items.map((item) => {
    return {
      item,
      checked: false,
    };
  });
}
