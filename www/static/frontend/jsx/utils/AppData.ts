const newWindow: any = window;

const appData: any = newWindow.appData;

/**
 * get route path by slug
 *
 * @param slug - route slug
 * @param routeParams - route params
 */
export const route = function (
  slug: string,
  ...routeParams: (string | number)[]
): string {
  let path = appData.routes[slug];

  if (!path) {
    console.error("Unknown path " + slug);
  }

  const interpolations = path.match(/{\w+:\w+}/gi);

  if (!interpolations) {
    return path;
  }

  if (interpolations.length !== routeParams.length) {
    throw new Error(
      `Expected ${interpolations.length} parameter(s) but got ${routeParams.length}`
    );
  }

  for (let i = 0; i < interpolations.length; i++) {
    path = path.replace(interpolations[i], <string>routeParams[i]);
  }

  return path;
};

export default appData;
