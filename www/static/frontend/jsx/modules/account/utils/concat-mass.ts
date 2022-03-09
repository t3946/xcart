export function concatMass<T, D>(mass: T[]): Array<T | D> {
  const newMass: Array<T | D> = [];

  mass.forEach((e) => newMass.push(e));

  return newMass;
}
