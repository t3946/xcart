export function editSelectViewValue(items, value: string) {
  return items.map(([e]) => {
    e.name = value + " " + e.name;
    return [e];
  });
}
