import React from "react";

/**
 * validate files from input file on max file size
 */
export default function validatorMaxFileSize(
  inputRef: React.MutableRefObject<Record<any, any>>,
  maxSizeMB: number
): (value: any, testContext: any) => boolean {
  const maxSizeB = maxSizeMB * 1024 * 1024;

  return function (): boolean {
    const files = inputRef.current.files;
    const filesNumber = files.length;

    for (let i = 0; i < filesNumber; i++) {
      const file = files[i];

      if (file.size > maxSizeB) {
        return false;
      }
    }

    return true;
  };
}
