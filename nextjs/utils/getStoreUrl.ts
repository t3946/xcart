// get cloud store url from local link
export function getStoreUrl(link: string): string {
  if (!link) {
    return "";
  }

  return `https://i1.s3stores.com/${link}`;
}

export default getStoreUrl;
