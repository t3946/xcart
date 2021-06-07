export const addPrefixToSubject = (
  firstValue: string,
  secondValue: string,
  subject: string
): string => {
  const prefix = subject.split(" ")[0];

  const subjectWordsMass = subject.split(" ");

  if (prefix === firstValue) {
    return subject;
  }
  if (prefix === secondValue) {
    return subjectWordsMass
      .map((e, index) => {
        if (index === 0) {
          return firstValue;
        }
        return e;
      })
      .join(" ");
  }

  subjectWordsMass.splice(0, 0, firstValue);

  return subjectWordsMass.join(" ");
};
//переписать это говно
