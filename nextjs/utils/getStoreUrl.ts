// get cloud store url from local link
export function getStoreUrl(link: string) {
  if (!link) {
    return null;
  }

  return `https://i1.s3stores.com/${link}`;
}

export default getStoreUrl;
