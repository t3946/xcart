module.exports = function addressToString(address) {
  const parts = [];
  const fields = ["street", "city", "state", "zip", "country"];

  for (const field of fields) {
    parts.push(address[field]);
  }

  return parts.join(", ");
};
