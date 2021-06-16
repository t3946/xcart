export function editObjectField<T, D>(
  object: T,
  field: string,
  newValue: D
): any {
  if (typeof object !== "object" || object === null) {
    return false;
  }
  return Object.fromEntries(
    Object.entries(object).map(([key, value]) => {
      if (key === field) {
        return [key, newValue];
      } else {
        if (Array.isArray(value)) return [key, value];
        const editValue = editObjectField(value, field, newValue);
        if (editValue) {
          return [key, editValue];
        }
        return [key, value];
      }
    })
  );
}

// export function getFieldValue(object, field: string) {
//   return Object.fromEntries(
//     Object.entries(object).reduce((accumulator, [key, value]) => {
//       if (key === field && typeof value !== "object") {
//         return accumulator.concat([[key, value]]);
//       } else if (typeof value === "object" && value !== null) {
//         const editValue = getFieldValue(value, field);
//         if (editValue && editValue !== [] && editValue !== {}) {
//           return accumulator.concat([[key, editValue]]);
//         }
//       }
//       return accumulator;
//     }, [])
//   );
// }
