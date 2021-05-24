export function editCheckedItems(
  checkedItems,
  prevValue,
  thisValue,
  items,
  multiply
) {
  const isChecked = checkedItems.find(
    (item) => item === items[thisValue].item.id
  );
  if (multiply) {
    items.map((e, index) => {
      if (prevValue > thisValue) {
        if (index <= prevValue && index >= thisValue) {
          checkedItems = edit(checkedItems, isChecked, e.item.id);
        }
      } else {
        if (index >= prevValue && index <= thisValue) {
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

function edit(items, checked, id) {
  if (checked) {
    return items.filter((item) => item !== id);
  }
  return items.concat(id);
}
