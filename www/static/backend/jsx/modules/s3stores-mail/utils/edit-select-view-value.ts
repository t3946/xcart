export function editSelectViewValue(items, value: string) {
  return items.map(([e]) => {
    const wordNameMass = e.name.split(" ");

    wordNameMass.splice(0, 0, value);

    e.name = wordNameMass.join(" ");
    return [e];
  });
}
