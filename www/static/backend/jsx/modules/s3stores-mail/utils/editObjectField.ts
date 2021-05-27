export function editObjectField(object, field, newValue) {
  if (typeof object !== "object" || object === null) {
    return false;
  }
  return Object.fromEntries(
    Object.entries(object).map(([key, value]) => {
      if (key === field) {
        return [key, newValue];
      } else {
        const editValue = editObjectField(value, field, newValue);
        if (editValue) {
          return [key, editValue];
        }
        return [key, value];
      }
    })
  );
}

export function getFieldValue(object, field) {
  if (typeof object === "object" && object !== null) {
    const a = Object.entries(object).filter(([key, value]) => {
      if (key === field && typeof value !== "object") {
        return [key, value];
      } else {
        const editValue = getFieldValue(value, field);
        if (editValue) {
          console.log(editValue);
          return editValue;
        }
      }
    });
    return a;
  }
}
