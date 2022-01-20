import React from "react";
import { Form } from "react-bootstrap";
import { FeedbackProps } from "react-bootstrap/Feedback";
import Styles from "@modules/ui/forms/Feedback.module.scss";
import cn from "classnames";

const Feedback: React.FC<FeedbackProps> = (props: FeedbackProps) => {
  const newProps: FeedbackProps = { ...props };

  newProps.className = cn([
    {
      [Styles.feedback_invalid]: props.type === "invalid",
      [Styles.feedback_valid]: props.type === "valid",
    },
    props.className,
    Styles.feedback,
  ]);

  return <Form.Control.Feedback {...newProps} />;
};

export default Feedback;
