export const convertCartNumber = (qtyNewNum: number): string => {
  if (qtyNewNum >= 10000 && qtyNewNum < 1000000) {
    return `${qtyNewNum.toString().slice(0, qtyNewNum.toString().length - 3)}K`;
  } else if (qtyNewNum >= 1000000) {
    return `${qtyNewNum.toString().slice(0, qtyNewNum.toString().length - 6)}M`;
  } else {
    return qtyNewNum.toLocaleString("en-US");
  }
};
