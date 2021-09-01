export const addPrefixToSubject = (
  firstValue: string,
  secondValue: string,
  subject: string
): string => {
  if (!subject) {
    return firstValue;
  }
  const prefix = subject.split(" ")[0];

  if (prefix === firstValue) {
    return subject;
  }
  if (prefix === secondValue) {
    return subject.replace(secondValue, firstValue);
  }

  const wordMass = subject.split(" ");

  wordMass.splice(0, 0, firstValue);

  return wordMass.join(" ");
};
