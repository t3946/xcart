import React from "react";

/**
 * validate files from input file on max file size
 */
export default function validatorFileFormat(
  inputRef: React.MutableRefObject<Record<any, any>>,
  formats: string[]
): (value: any, testContext: any) => boolean {
  return function (): boolean {
    const files = inputRef.current.files;
    const filesNumber = files.length;

    for (let i = 0; i < filesNumber; i++) {
      const file = files[i];

      if (!formats.includes(file.type)) {
        return false;
      }
    }

    return true;
  };
}
