/**
 * @param key получение значения из appData по ключу. Если значение не найдено, вернёт undefined.
 * Ключ может быть строкой, или набором строк соединённых точками, которые позволяет выбирать
 * вложенные значения
 */
const appData = (key: string): any => {
  if (key) {
    const keys = key.split(".");

    let value = window.appData;
    let i = 0;

    while (keys[i]) {
      value = value[keys[i]];

      if (value === undefined) {
        return undefined;
      }

      i++;
    }

    return value;
  }

  return window.appData;
};

export default appData;
