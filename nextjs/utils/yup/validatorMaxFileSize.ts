import React from "react";
import mbToB from "@utils/mbToB";

export interface ICustomFormatProps {
  maxSizeMB: number;
  formats: string[];
}

/**
 * validate files from input file on max file size
 */
export default function validatorMaxFileSize(
  inputRef: React.MutableRefObject<Record<any, any>>,
  size: number | ICustomFormatProps[]
): (value: any, testContext: any) => boolean {
  function getMaxSizeB(file) {
    if (typeof size === "number") {
      return mbToB(size);
    } else {
      for (let i = 0; i < size.length; i++) {
        const { formats, maxSizeMB } = size[i];

        if (formats.includes(file.type)) {
          return mbToB(maxSizeMB);
        }
      }
    }
  }

  return function (): boolean {
    const files = inputRef.current.files;
    const filesNumber = files.length;

    for (let i = 0; i < filesNumber; i++) {
      const file = files[i];

      if (file.size > getMaxSizeB(file)) {
        return false;
      }
    }

    return true;
  };
}
