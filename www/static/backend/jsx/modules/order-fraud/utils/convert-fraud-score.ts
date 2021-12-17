export const convertResult = (template) => {
  if (template.fraud_score - Math.round(template.fraud_score) === 0) {
    return template.fraud_score;
  } else {
    const convertOutcome =
      (Math.round(template.outcome * 6) / 6) * template.question_weight;
    return convertOutcome.toFixed(2);
  }
};
