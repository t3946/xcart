export function concatMass<T, D>(mass: T[]): D[] {
  const newMass: D[] = [];

  mass.forEach((e) => newMass.push(e));

  return newMass;
}
