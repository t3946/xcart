export const getHeaderClassByName = (name) => {
  if (["FN(CI)", "FN(SA)", "FN(BA)", "SA", "BA"].includes(name)) {
    return "table-item-wrapper-red";
  } else if (["FN(CH)"].includes(name)) {
    return "table-item-wrapper-green";
  }
  return "table-item-wrapper-blue";
};
