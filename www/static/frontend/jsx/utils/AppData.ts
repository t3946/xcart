const newWindow: any = window;
const appData = newWindow.appData;

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

  const interpolations = path.match(/{\w+:\w+}/gi);

  if (interpolations.length !== routeParams.length) {
    throw new Error(
      `Expected ${interpolations.length} parameter(s) but got ${routeParams.length}`
    );
  }

  for (let i = 0; i < interpolations.length; i++) {
    path = path.replace(interpolations[i], routeParams[i]);
  }

  return path;
};
