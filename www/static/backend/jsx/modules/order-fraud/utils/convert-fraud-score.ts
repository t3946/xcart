export const convertResult = (template) => {
  if (template.fraud_score - Math.round(template.fraud_score) === 0) {
    return template.fraud_score;
  } else {
    const convertOutcome =
      (Math.round(template.outcome * template.coefficient) /
        template.coefficient) *
      template.question_weight;
    if (convertOutcome - Math.round(convertOutcome) === 0) {
      return convertOutcome.toFixed(2);
    }
  }
  return template.fraud_score;
};
