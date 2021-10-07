export const validFileData = (obSelect: object, checkMain: object): boolean => {
  for (const iTable in obSelect) {
    const keyField = Object.keys(obSelect[iTable]).map((el) => {
      return obSelect[iTable][el];
    });
    if (!keyField.includes(checkMain[iTable])) {
      return false;
    }
  }
  return true;
};
