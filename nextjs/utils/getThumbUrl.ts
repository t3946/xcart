export function getThumbUrl(url: string) {
  const prefix = "thumb_";
  //find last slash
  const i = url.search(/\/\w+\.\w+$/);
  const before = url.substring(0, i + 1);
  const after = prefix + url.substring(i + 1);

  return before + after;
}

export default getThumbUrl;
