import React from "react";
import { ViewValidPropsDto } from "@/modules/shared/ts/types";

export const InputViewValid: React.FC<ViewValidPropsDto> = ({ src }) => {
  return <img src={src} className="input-validate-icons" aria-hidden="true" />;
};

export default InputViewValid;
