import React from "react";
import Label from "@modules/ui/forms/Label";
import Feedback from "@modules/ui/forms/Feedback";

interface IProps {
  input: React.ReactNode;
  error?: string | false | undefined;
  label: string | React.ReactNode;
}

const FormGroup: React.FC<IProps> = ({ input, error, label }) => {
  return (
    <div className="row align-items-center justify-content-between mb-20">
      <Label className={"col-md-3 col-lg-4 mb-10 mb-md-0"}>{label}</Label>
      <div className="col-md-9 col-lg-8">
        {input}
        {error && (
          <Feedback className={"position-absolute d-block"} type="invalid">
            {error}
          </Feedback>
        )}
      </div>
    </div>
  );
};

export default FormGroup;
