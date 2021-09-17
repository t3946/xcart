export function getValuesForSelect(mass, value, viewValue) {
  return mass.map((e) => {
    return {
      value: e[value],
      viewValue: e[viewValue],
    };
  });
}
