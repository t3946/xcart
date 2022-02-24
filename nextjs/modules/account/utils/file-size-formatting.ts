export function bToKB(bytes: number) {
  return Math.ceil(bytes / 1024);
}

export function bToMB(bytes: number) {
  return Math.ceil(bytes / 1048576);
}

export function fileSizeFormat(bytes: number) {
  if (bytes < 1024) {
    return `${bytes}B`;
  } else if (bytes < 1048576) {
    return `${bToKB(bytes)}KB`;
  }
  return `${bToMB(bytes)}MB`;
}
