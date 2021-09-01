export function checkFileExtension(fileName: string, src: string) {
  const wordsMass = fileName.split(".");

  const extension = wordsMass[wordsMass.length - 1].toLowerCase();

  if (
    extension === "svg" ||
    extension === "png" ||
    extension === "jpg" ||
    extension === "gif" ||
    extension === "jpeg"
  ) {
    if (extension === "jpeg") {
      return {
        image: true,
        iconMini: `/static/backend/images/icons/file-jpg-mini.svg`,
        src,
      };
    }
    return {
      image: true,
      iconMini: `/static/backend/images/icons/file-${extension}-mini.svg`,
      src,
    };
  }

  return {
    image: false,
    icon: `/static/backend/images/icons/file-${extension}.svg`,
    iconMini: `/static/backend/images/icons/file-${extension}-mini.svg`,
  };
}
