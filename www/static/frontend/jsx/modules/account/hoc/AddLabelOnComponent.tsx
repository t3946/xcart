import React from "react";

interface addLabelOnComponentDto {
  label: string;
  component: React.FC;
}

export const AddLabelOnComponent: React.FC<addLabelOnComponentDto> = () => {
  return <div></div>;
};
