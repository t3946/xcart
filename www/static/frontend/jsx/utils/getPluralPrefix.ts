export const getPluralPrefix = (count: number): string => {
  switch (app.options.lang) {
    case "ru":
      const value = Math.abs(count) % 100;
      const num = value % 10;
      if (value > 10 && value < 20) return "_other";
      if (num > 1 && num < 5) return "_many";
      if (num == 1) return "_one";
      return "_other";
    default:
      if (count === 1) {
        return "_one";
      } else {
        return "_many";
      }
  }
};
