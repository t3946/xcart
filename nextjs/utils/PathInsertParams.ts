/**
 * insert params in route
 *
 * @param path - route slug
 * @param params - route params
 */
const PathInsertParams = function (
  path: string,
  ...params: (string | number)[]
): string {
  const interpolations = path.match(/{.+?:.+?}/gi);

  if (!interpolations) {
    return path;
  }

  if (!params.length) {
    return path.replace(/{.*?:/gi, ":").replace(/}/gi, "");
  }

  if (interpolations.length !== params.length) {
    throw new Error(
      `Expected ${interpolations.length} parameter(s) but got ${params.length}`
    );
  }

  for (let i = 0; i < interpolations.length; i++) {
    path = path.replace(interpolations[i], <string>params[i]);
  }

  return path;
};

export default PathInsertParams;
